<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Application;

use Psr\SimpleCache\CacheInterface;
use Railt\Foundation\Application;
use Railt\Foundation\Config\RepositoryInterface;
use Railt\Foundation\Extension\Extension;
use Railt\Foundation\Extension\Status;
use Railt\Io\Exception\NotReadableException;
use Railt\Io\File;
use Railt\SDL\Compiler;
use Railt\SDL\Contracts\Definitions\Definition;
use Railt\SDL\Reflection\Dictionary;
use Railt\SDL\Schema\CompilerInterface;
use Railt\SDL\Schema\Configuration;
use Railt\Storage\Storage;

/**
 * Class CompilerExtension
 */
class CompilerExtension extends Extension
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'SDL';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'GraphQL SDL compiler integration';
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return Application::VERSION;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return Status::STABLE;
    }

    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->registerIfNotRegistered(CompilerInterface::class, function () {
            $cache = $this->app->has(CacheInterface::class) ? $this->app->make(CacheInterface::class) : null;

            return new Compiler($cache);
        });

        $this->app->alias(CompilerInterface::class, Compiler::class);

        $this->app->registerIfNotRegistered(Dictionary::class, function (CompilerInterface $compiler) {
            /** @var Configuration $compiler */
            return $compiler->getDictionary();
        });
    }

    /**
     * @param RepositoryInterface $config
     * @param CompilerInterface $compiler
     * @throws NotReadableException
     */
    public function boot(RepositoryInterface $config, CompilerInterface $compiler): void
    {
        $this->autoload($config, $compiler);

        if (! $this->env->isRunningInConsole()) {
            $this->preload($config, $compiler);
        }
    }

    /**
     * @param RepositoryInterface $config
     * @param CompilerInterface $compiler
     */
    private function autoload(RepositoryInterface $config, CompilerInterface $compiler): void
    {
        $this->autoloadFiles($config, $compiler);
        $this->autoloadPaths($config, $compiler);
        $this->autoloadFromSameFile($config, $compiler);
    }

    /**
     * @param RepositoryInterface $config
     * @param CompilerInterface $compiler
     */
    private function autoloadFiles(RepositoryInterface $config, CompilerInterface $compiler): void
    {
        $compiler->autoload(function (string $type) use ($config) {
            $files = (array)$config->get(RepositoryInterface::KEY_AUTOLOAD_FILES, []);

            foreach ($files as $file) {
                $files[\pathinfo($file, \PATHINFO_FILENAME)] = $file;
            }

            if (isset($files[$type])) {
                return File::fromPathname($files[$type]);
            }

            return null;
        });
    }

    /**
     * @param RepositoryInterface $config
     * @param CompilerInterface $compiler
     */
    private function autoloadPaths(RepositoryInterface $config, CompilerInterface $compiler): void
    {
        $compiler->autoload(function (string $type) use ($config) {
            [$paths, $extensions] = [
                (array)$config->get(RepositoryInterface::KEY_AUTOLOAD_PATHS, []),
                (array)$config->get(RepositoryInterface::KEY_AUTOLOAD_EXTENSIONS, []),
            ];

            foreach ($paths as $path) {
                foreach ($extensions as $extension) {
                    $pathname = $path . '/' . $type . '.' . $extension;

                    if (\is_file($pathname)) {
                        return File::fromPathname($pathname);
                    }
                }
            }

            return null;
        });
    }

    /**
     * @param RepositoryInterface $config
     * @param CompilerInterface $compiler
     */
    private function autoloadFromSameFile(RepositoryInterface $config, CompilerInterface $compiler): void
    {
        $compiler->autoload(function (string $type, ?Definition $from) use ($config) {
            if ($from === null) {
                return null;
            }

            $file = $from->getDocument()->getFile();

            if (! $file->isFile()) {
                return null;
            }

            $autoload = (array)$config->get(RepositoryInterface::KEY_AUTOLOAD_EXTENSIONS, []);

            foreach ($autoload as $extension) {
                $pathname = \dirname($file->getPathname()) . '/' . $type . '.' . $extension;

                if (\is_file($pathname)) {
                    return File::fromPathname($pathname);
                }
            }

            return null;
        });
    }

    /**
     * @param RepositoryInterface $config
     * @param CompilerInterface $compiler
     * @throws NotReadableException
     */
    private function preload(RepositoryInterface $config, CompilerInterface $compiler): void
    {
        $this->preloadFiles($config, $compiler);
        $this->preloadPaths($config, $compiler);
    }

    /**
     * @param RepositoryInterface $config
     * @param CompilerInterface $compiler
     * @throws NotReadableException
     */
    private function preloadFiles(RepositoryInterface $config, CompilerInterface $compiler): void
    {
        $files = (array)$config->get(RepositoryInterface::KEY_PRELOAD_FILES, []);

        foreach ($files as $file) {
            $compiler->compile(File::fromPathname($file));
        }
    }

    /**
     * @param RepositoryInterface $config
     * @param CompilerInterface $compiler
     * @throws NotReadableException
     */
    private function preloadPaths(RepositoryInterface $config, CompilerInterface $compiler): void
    {
        [$paths, $extensions] = [
            (array)$config->get(RepositoryInterface::KEY_PRELOAD_PATHS, []),
            (array)$config->get(RepositoryInterface::KEY_PRELOAD_EXTENSIONS, []),
        ];

        foreach ($extensions as $extension) {
            foreach ($paths as $path) {
                $files = \glob($path . '*' . '.' . $extension);

                foreach ($files as $file) {
                    $compiler->compile(File::fromPathname($file));
                }
            }
        }
    }
}
