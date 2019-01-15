<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\ClassLoader;

use Railt\Foundation\Application;
use Railt\Foundation\Extension\Extension;
use Railt\Foundation\Extension\Status;
use Railt\Io\File;
use Railt\SDL\Schema\CompilerInterface;

/**
 * Class KernelExtension
 */
class ClassLoaderExtension extends Extension
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'ClassLoader';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Provides the ability to reference PHP code from within GraphQL SDL files.';
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
        $this->registerIfNotRegistered(ClassLoaderInterface::class, function () {
            return new DirectiveClassLoader();
        });
    }

    /**
     * @param CompilerInterface $compiler
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public function boot(CompilerInterface $compiler): void
    {
        $compiler->compile(File::fromPathname(__DIR__ . '/../../resources/class-loader.graphqls'));
    }
}
