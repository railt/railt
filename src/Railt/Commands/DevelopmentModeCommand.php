<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Commands;

use Composer\Autoload\ClassLoader;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * Class DevelopmentModeCommand
 * @package Railt\Commands
 */
class DevelopmentModeCommand extends AbstractCommand
{
    /**
     * @var string
     */
    protected $name = 'devel:init';

    /**
     * @var string
     */
    protected $description = 'Initialize the development mode';

    /**
     * @var array
     */
    protected $options = [
        'force' => 'Force reinitialize project for development.'
    ];

    /**
     * @var array
     */
    private $packages = [
        'railt/http'            => 'Http',
        'railt/parser'          => 'Parser',
        'railt/routing'         => 'Routing',
        'railt/reflection'      => 'Reflection',
        'railt/webonyx-adapter' => 'Adapters/Webonyx',
    ];

    /**
     * @return void
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     * @throws \InvalidArgumentException
     * @throws \ReflectionException
     */
    public function handle(): void
    {
        $projectRoot = $this->projectRoot();

        $packages = $this->vendors($this->packages);

        foreach ($packages as $package => $directory) {
            if ($this->detect($directory, $package)) {
                $this->out->writeln('');
                $this->cloneVendor($directory, $package);

                $this->out->write('Linking...');
                $this->linkVendor($projectRoot, $directory, $this->packages[$package] ?? $package);
                $this->out->writeln("\r" . '<info>OK</info>' . str_repeat(' ', 20) . "\n");
            }
        }
    }

    /**
     * @param array $packages
     * @return iterable|string[]
     * @throws \ReflectionException
     */
    private function vendors(array $packages): iterable
    {
        $vendor = $this->vendorDirectory();

        foreach ($packages as $package => $ns) {
            yield $package => $vendor . DIRECTORY_SEPARATOR . $package;
        }
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    private function vendorDirectory(): string
    {
        $reflection = new \ReflectionClass(ClassLoader::class);

        return dirname($reflection->getFileName(), 2);
    }

    /**
     * @param string $dir
     * @param string $package
     * @return bool
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     */
    private function detect(string $dir, string $package): bool
    {
        $this->out->writeln('<comment>Initializing ' . $package . '.</comment>');
        if (is_dir($dir)) {
            $this->out->write('Found.');
            $git = $dir . DIRECTORY_SEPARATOR . '.git';

            if (is_dir($git) && !$this->input->getOption('force')) {
                $this->out->writeln("\r" . '<error>Package ' . $package . ' already initialized.</error>');
                return false;
            }

            $this->out->write("\r" . 'Flushing ' . $package . ' root...');
            $this->clearDirectory($dir);

            $this->out->write("\r" . str_repeat(' ', 50));
        }

        return true;
    }

    /**
     * @param string $dir
     * @param string $package
     * @return string
     */
    private function cloneVendor(string $dir, string $package): string
    {
        $command = 'git clone https://github.com/%s.git "%s"';

        return (string)shell_exec(sprintf($command, $package, $dir));
    }

    /**
     * @param string $project
     * @param string $package
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     */
    private function linkVendor(string $project, string $package, string $namespace): void
    {
        $tests   = $project . '/tests/' . $namespace;
        $sources = $project . '/src/Railt/' . $namespace;

        $fs = new Filesystem();

        if (file_exists($sources)) {
            $this->clearDirectory($sources);
        }

        $fs->symlink($package . DIRECTORY_SEPARATOR . 'src', $sources);

        if (file_exists($tests)) {
            $this->clearDirectory($tests);
        }

        $fs->symlink($package . DIRECTORY_SEPARATOR . 'tests', $tests);
    }

    /**
     * @param string $dir
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     * @throws \InvalidArgumentException
     */
    private function clearDirectory(string $dir): void
    {
        if (is_link($dir)) {
            unlink($dir);
            return;
        }

        $files = (new Finder())->in($dir)
            ->ignoreDotFiles(false)
            ->ignoreUnreadableDirs(false)
            ->ignoreVCS(false);

        $fs = new Filesystem();

        $fs->remove($files);
        $fs->remove($dir);
    }
}
