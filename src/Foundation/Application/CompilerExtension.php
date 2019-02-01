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
use Railt\Io\Readable;
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
     * @var string[]
     */
    private const FILE_EXTENSIONS = [
        '.graphqls',
        '.graphql',
        '.gql',
    ];

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
     * @param string $fileWithoutExtension
     * @return string|null
     */
    private function findByFileWithoutExtension(string $fileWithoutExtension): ?string
    {
        foreach (self::FILE_EXTENSIONS as $extension) {
            $pathname = $fileWithoutExtension . $extension;

            if (\is_file($pathname)) {
                return $pathname;
            }
        }

        return \is_file($fileWithoutExtension) ? $fileWithoutExtension : null;
    }

    /**
     * @param string $dirname
     * @param string $type
     * @return Readable|null
     * @throws \Railt\Io\Exception\NotReadableException
     */
    private function findByDirectoryAndType(string $dirname, string $type): ?Readable
    {
        $result = $this->findByFileWithoutExtension($dirname . '/' . $type);

        if (\is_string($result)) {
            return File::fromPathname($result);
        }

        return null;
    }

    /**
     * @throws ContainerInvocationException
     */
    public function register(): void
    {
        $resolver = function (ApplicationInterface $app, Storage $cache) {
            $compiler = new Compiler($cache);

            $this->registerBasicAutoloaderLogic($compiler);
            $this->registerAutoloadPaths($app, $compiler);

            return $compiler;
        };

        $this->registerIfNotRegistered(CompilerInterface::class, $resolver);
    }

    /**
     * @param Compiler $compiler
     */
    private function registerBasicAutoloaderLogic(Compiler $compiler): void
    {
        $compiler->autoload(function (string $type, ?TypeDefinition $from): ?Readable {
            if ($from === null) {
                return null;
            }

            if (! ($file = $from->getDocument()->getFile())->isFile()) {
                return null;
            }

            return $this->findByDirectoryAndType(\dirname($file->getPathname()), $type);
        });
    }

    /**
     * @param ApplicationInterface $app
     * @param Compiler $compiler
     */
    private function registerAutoloadPaths(ApplicationInterface $app, Compiler $compiler): void
    {
        if ($app instanceof Application) {
            $compiler->autoload(function (string $type) use ($app): ?Readable {
                foreach ($app->getAutoloadPaths() as $directory) {
                    if ($result = $this->findByDirectoryAndType($directory, $type)) {
                        return $result;
                    }
                }

                return null;
            });
        }
    }
}
