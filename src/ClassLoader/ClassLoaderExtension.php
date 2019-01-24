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
use Railt\Foundation\Application\CompilerExtension;
use Railt\Foundation\Extension\Extension;
use Railt\Foundation\Extension\Status;
use Railt\Io\Exception\NotReadableException;
use Railt\Io\File;
use Railt\SDL\Schema\CompilerInterface;

/**
 * Class KernelExtension
 */
class ClassLoaderExtension extends Extension
{
    /**
     * @var string
     */
    private const SOURCES_PATHNAME = __DIR__ . '/../../resources/class-loader.graphqls';

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
     * @return array
     */
    public function getDependencies(): array
    {
        return ['railt/railt' => CompilerExtension::class];
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
        $this->registerIfNotRegistered(ClassLoaderInterface::class, function (CompilerInterface $compiler) {
            return new DirectiveClassLoader($compiler, $this->app);
        });
    }

    /**
     * @param CompilerInterface $compiler
     * @throws NotReadableException
     */
    public function boot(CompilerInterface $compiler): void
    {
        $compiler->compile(File::fromPathname(self::SOURCES_PATHNAME));
    }
}
