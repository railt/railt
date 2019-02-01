<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Application;

use Railt\Container\Exception\ContainerInvocationException;
use Railt\Foundation\Application;
use Railt\Foundation\ApplicationInterface;
use Railt\Foundation\Extension\Extension;
use Railt\Foundation\Extension\Status;
use Railt\Io\File;
use Railt\SDL\Compiler;
use Railt\SDL\Contracts\Definitions\TypeDefinition;
use Railt\SDL\Schema\CompilerInterface;
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
     * @return array
     */
    public function getDependencies(): array
    {
        return ['railt/railt' => CacheExtension::class];
    }

    /**
     * @return void
     * @throws ContainerInvocationException
     */
    public function register(): void
    {
        $this->registerIfNotRegistered(CompilerInterface::class, function (Storage $cache) {
            return new Compiler($cache);
        });
    }

    /**
     * @param CompilerInterface $compiler
     * @param ApplicationInterface $app
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public function boot(ApplicationInterface $app, CompilerInterface $compiler): void
    {
        if ($app instanceof Application) {
            $this->preload($app, $compiler);
            $this->autoload($app, $compiler);
        }
    }

    /**
     * @param Application $app
     * @param CompilerInterface $compiler
     */
    private function autoload(Application $app, CompilerInterface $compiler): void
    {
        $this->autoloadFiles($compiler, $app->getAutoloadFiles());
        $this->autoloadPaths($compiler, $app->getAutoloadPaths(), $app->getAutoloadExtensions());
        $this->autoloadFromSameFile($compiler, $app->getAutoloadExtensions());
    }

    /**
     * @param CompilerInterface $compiler
     * @param array|string[] $extensions
     */
    private function autoloadFromSameFile(CompilerInterface $compiler, array $extensions): void
    {
        $compiler->autoload(function (string $type, ?TypeDefinition $from) use ($extensions) {
            if ($from === null) {
                return null;
            }

            $file = $from->getDocument()->getFile();

            if (! $file->isFile()) {
                return null;
            }

            foreach ($extensions as $extension) {
                $pathname = \dirname($file->getPathname()) . '/' . $type . $extension;

                if (\is_file($pathname)) {
                    return File::fromPathname($pathname);
                }
            }

            return null;
        });
    }

    /**
     * @param Application $app
     * @param CompilerInterface $compiler
     * @throws \Railt\Io\Exception\NotReadableException
     */
    private function preload(Application $app, CompilerInterface $compiler): void
    {
        $this->preloadFiles($compiler, $app->getPreloadFiles());
        $this->preloadPaths($compiler, $app->getPreloadPaths(), $app->getPreloadExtensions());
    }

    /**
     * @param CompilerInterface $compiler
     * @param array|string[] $files
     * @throws \Railt\Io\Exception\NotReadableException
     */
    private function preloadFiles(CompilerInterface $compiler, array $files): void
    {
        foreach ($files as $file) {
            $compiler->compile(File::fromPathname($file));
        }
    }

    /**
     * @param CompilerInterface $compiler
     * @param array|string[] $paths
     * @param array|string[] $extensions
     * @throws \Railt\Io\Exception\NotReadableException
     */
    private function preloadPaths(CompilerInterface $compiler, array $paths, array $extensions): void
    {
        foreach ($extensions as $extension) {
            foreach ($paths as $path) {
                $files = \glob($path . '*' . $extension);

                foreach ($files as $file) {
                    $compiler->compile(File::fromPathname($file));
                }
            }
        }
    }

    /**
     * @param CompilerInterface $compiler
     * @param array|string[] $files
     */
    private function autoloadFiles(CompilerInterface $compiler, array $files): void
    {
        foreach ($files as $file) {
            $files[\pathinfo($file, \PATHINFO_FILENAME)] = $file;
        }

        $compiler->autoload(function (string $type) use ($files) {
            if (isset($files[$type])) {
                return File::fromPathname($files[$type]);
            }

            return null;
        });
    }

    /**
     * @param CompilerInterface $compiler
     * @param array|string[] $paths
     * @param array|string[] $extensions
     */
    private function autoloadPaths(CompilerInterface $compiler, array $paths, array $extensions): void
    {
        $compiler->autoload(function (string $type) use ($paths, $extensions) {
            foreach ($paths as $path) {
                foreach ($extensions as $extension) {
                    $pathname = $path . '/' . $type . $extension;

                    if (\is_file($pathname)) {
                        return File::fromPathname($pathname);
                    }
                }
            }

            return null;
        });
    }
}
