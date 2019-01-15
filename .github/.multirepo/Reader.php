<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;

/**
 * Class Reader
 */
class Reader
{
    /**
     * @var string
     */
    private const COMPOSER_PRIORITIES = [
        'name'              => 0,
        'license'           => 1,
        'type'              => 2,
        'version'           => 3,
        'homepage'          => 4,
        'description'       => 5,
        'keywords'          => 6,
        'authors'           => 7,
        'support'           => 8,
        'require'           => 9,
        'autoload'          => 10,
        'require-dev'       => 11,
        'autoload-dev'      => 12,
        'provide'           => 13,
        'config'            => 14,
        'prefer-stable'     => 15,
        'minimum-stability' => 16,
    ];

    /**
     * @var array
     */
    private $config;

    /**
     * @var string
     */
    private $root;

    /**
     * @var array|string[]
     */
    private $dirs = [];

    /**
     * Reader constructor.
     * @param string $path
     * @throws InvalidArgumentException
     */
    public function __construct(string $path)
    {
        $this->root   = \dirname($path);
        $this->config = \json_decode(\file_get_contents($path), true);

        if (\json_last_error() !== \JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('JSON Error: ' . \json_last_error_msg());
        }
    }

    /**
     * @return iterable
     * @throws InvalidArgumentException
     */
    public function getPackages(): iterable
    {
        $packages = \array_keys($this->config['packages'] ?? []);

        foreach ($packages as $key) {
            $remote = $this->config['packages'][$key]['remote'];

            yield $remote => $this->getPackageFiles($key);
        }
    }

    /**
     * @param string $name
     * @return Generator
     * @throws InvalidArgumentException
     */
    private function getPackageFiles(string $name): \Generator
    {
        $key = 'packages.' . $name;

        yield from $this->getGlobalFiles();

        yield from $this->readFiles($key);
        yield from $this->readFilesFromDirectory($key);
    }

    /**
     * @return iterable
     * @throws InvalidArgumentException
     */
    private function getGlobalFiles(): iterable
    {
        yield from $this->readFiles();
        yield from $this->readFilesFromDirectory();
    }

    /**
     * @param string|null $path
     * @return iterable
     */
    private function readFiles(string $path = null): iterable
    {
        $path = $path ? $path . '.files' : 'files';

        foreach ((array)\array_get($this->config, $path, []) as $from => $to) {
            if ($to === 'composer.json') {
                $this->patchVersion($this->absolute($from));
            }

            yield $this->absolute($from) => $to;
        }
    }

    /**
     * @param string $path
     */
    private function patchVersion(string $path): void
    {
        $array = \json_decode(\file_get_contents($path), true);

        $array['version'] = \Railt\Foundation\Application::VERSION;

        \uksort($array, function (string $a, string $b) {
            return (self::COMPOSER_PRIORITIES[$a] ?? 100) <=> (self::COMPOSER_PRIORITIES[$b] ?? 100);
        });

        \file_put_contents($path, \json_encode($array, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES) . "\n");
    }

    /**
     * @param string $path
     * @return string
     */
    private function absolute(string $path): string
    {
        $path = $this->root . '/' . $path;

        return \str_replace('\\', '/', $path);
    }

    /**
     * @param string|null $path
     * @return iterable
     * @throws InvalidArgumentException
     */
    private function readFilesFromDirectory(string $path = null): iterable
    {
        $path = $path ? $path . '.dirs' : 'dirs';

        $directories = (array)\array_get($this->config, $path, []);

        foreach ($directories as $from => $directory) {
            $files = (new Finder())->files()->in($this->absolute($from));

            /** @var \Symfony\Component\Finder\SplFileInfo $file */
            foreach ($files as $file) {
                yield $file->getRealPath() => $directory . '/' . $file->getRelativePathname();
            }
        }
    }

    /**
     * @param string $remote
     * @param string $path
     * @return string
     * @throws \Symfony\Component\Process\Exception\LogicException
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     */
    public function clone(string $remote, string $path): string
    {
        $this->dirs[] = $path = $this->absolute($path);

        $this->clear($path);
        echo $this->exec('git clone %s "%s"', $remote, \escapeshellarg($path));

        return $path;
    }

    /**
     * @param string $dir
     * @throws \Symfony\Component\Process\Exception\LogicException
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     */
    private function clear(string $dir): void
    {
        if (\is_dir($dir)) {
            echo $this->exec('rm -rf %s', $dir);
        }
    }

    /**
     * @param string $cmd
     * @param mixed ...$args
     * @return string
     * @throws \Symfony\Component\Process\Exception\LogicException
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     */
    private function exec(string $cmd, ...$args): string
    {
        $cmd = \vsprintf($cmd, $args);
        echo 'Execute: ' . $cmd . "\n";

        $process = new Process($cmd, $this->root);
        $process->run();

        return (string)$process->getOutput();
    }

    /**
     * @param string $path
     * @param iterable $files
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function update(string $path, iterable $files): void
    {
        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ((new Finder())->files()->ignoreDotFiles(false)->in($path) as $file) {
            \unlink($file->getRealPath());
        }

        foreach ($files as $from => $to) {
            $to = $path . '/' . $to;

            if (! @\mkdir(\dirname($to), 0777, true) && ! \is_dir(\dirname($to))) {
                throw new \RuntimeException('Error while creating directory for ' . \dirname($to));
            }

            \copy($from, $to);
        }
    }

    /**
     * @param string $path
     * @throws \Symfony\Component\Process\Exception\LogicException
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     */
    public function commit(string $path): void
    {
        $message = $this->exec('cd "%s" & git log -1 --pretty=%%B',
            $this->absolute($this->config['root'] ?? './'));

        $message = \trim($message);

        echo $this->exec('cd "%s" & git add ./', $path);
        echo $this->exec('cd "%s" & git commit -m "%s"', $path, $message);
        echo $this->exec('cd "%s" & git push origin master', $path);
    }

    /**
     * @throws \Symfony\Component\Process\Exception\LogicException
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     */
    public function __destruct()
    {
        foreach ($this->dirs as $dir) {
            $this->clear($dir);
        }
    }
}
