<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Extension;

use Ramsey\Collection\Map\TypedMap;
use Railt\Container\ContainerInterface;
use Railt\Foundation\Extension\Exception\ExtensionException;
use Railt\Container\Exception\ContainerInvocationException;
use Railt\Container\Exception\ContainerResolutionException;
use Railt\Container\Exception\ParameterResolutionException;

/**
 * Class Repository
 */
class Repository implements RepositoryInterface
{
    /**
     * @var string
     */
    protected const EXTENSION_REGISTER_METHOD = 'register';

    /**
     * @var string
     */
    protected const EXTENSION_BOOT_METHOD = 'boot';

    /**
     * @var string
     */
    private const ERROR_UNMET_DEPENDENCY = 'Could not load extension "%s" from [%s %s].';

    /**
     * @var string
     */
    private const ERROR_UNMET_DEPENDENCY_PACKAGE = 'You need to require the project dependency "%s" using Composer';

    /**
     * @var string
     */
    private const ERROR_UNMET_DEPENDENCY_CLASS = 'Extension class %s not found or could not be loaded';

    /**
     * @var ContainerInterface
     */
    private ContainerInterface $app;

    /**
     * @var array|string[]
     */
    private array $booted = [];

    /**
     * @var TypedMap|ExtensionInterface[]
     */
    private TypedMap $extensions;

    /**
     * Repository constructor.
     *
     * @param ContainerInterface $app
     */
    public function __construct(ContainerInterface $app)
    {
        $this->app = $app;
        $this->extensions = new TypedMap('string', ExtensionInterface::class);
    }

    /**
     * @param ExtensionInterface|string $extension
     * @return void
     * @throws ParameterResolutionException
     * @throws ContainerInvocationException
     * @throws ContainerResolutionException
     * @throws ExtensionException
     */
    public function add($extension): void
    {
        if ($this->isRegistered($extension)) {
            return;
        }

        $this->register($extension);
    }

    /**
     * @param ExtensionInterface|string $extension
     * @return ExtensionInterface
     * @throws ContainerInvocationException
     * @throws ExtensionException
     */
    protected function register($extension): ExtensionInterface
    {
        $this->extensions->put($this->key($extension), $instance = $this->make($extension));

        $this->loadDependencies($instance);
        $this->fireRegistration($instance);

        return $instance;
    }

    /**
     * @param ExtensionInterface|string $extension
     * @return ExtensionInterface
     */
    protected function make($extension): ExtensionInterface
    {
        if (\is_string($extension)) {
            return $this->app->make($extension);
        }

        return $extension;
    }

    /**
     * @param ExtensionInterface|string $extension
     * @return bool
     */
    protected function isRegistered($extension): bool
    {
        return $this->extensions->containsKey($this->key($extension));
    }

    /**
     * @param string|ExtensionInterface $extension
     * @return string
     */
    protected function key($extension): string
    {
        return \is_string($extension) ? $extension : \get_class($extension);
    }

    /**
     * @param ExtensionInterface $extension
     * @throws ContainerInvocationException
     * @throws ParameterResolutionException
     * @throws ContainerResolutionException
     * @throws ExtensionException
     */
    private function loadDependencies(ExtensionInterface $extension): void
    {
        foreach ($extension->getDependencies() as $name => $dependency) {
            if (! \class_exists($dependency)) {
                throw $this->unmetDependency($extension, $dependency, $name);
            }

            if (! $this->isRegistered($dependency)) {
                $this->add($dependency);
            }
        }
    }

    /**
     * @param ExtensionInterface $from
     * @param string $dependency
     * @param $package
     * @return ExtensionException
     */
    private function unmetDependency(ExtensionInterface $from, string $dependency, $package): ExtensionException
    {
        \assert(\is_int($package) || \is_string($package));

        $header = \vsprintf(self::ERROR_UNMET_DEPENDENCY, [
            $dependency,
            $from->getName(),
            $from->getVersion(),
        ]);

        $description = \is_string($package)
            ? \sprintf(self::ERROR_UNMET_DEPENDENCY_PACKAGE, $package)
            : \sprintf(self::ERROR_UNMET_DEPENDENCY_CLASS, $dependency);

        return new ExtensionException(\implode("\n", [$header, $description]));
    }

    /**
     * @param ExtensionInterface $extension
     * @throws ContainerInvocationException
     */
    private function fireRegistration(ExtensionInterface $extension): void
    {
        $method = [$extension, self::EXTENSION_REGISTER_METHOD];

        if (\method_exists(...$method)) {
            $this->app->call($method);
        }
    }

    /**
     * @return \Traversable|ExtensionInterface[]
     */
    public function getIterator(): \Traversable
    {
        return $this->extensions->getIterator();
    }

    /**
     * @throws ContainerInvocationException
     */
    public function boot(): void
    {
        foreach ($this->extensions as $extension) {
            if (! $this->isBooted($extension)) {
                $this->fireBoot($extension);
            }
        }
    }

    /**
     * @param string|ExtensionInterface $extension
     * @return bool
     */
    protected function isBooted($extension): bool
    {
        return \in_array($this->key($extension), $this->booted, true);
    }

    /**
     * @param ExtensionInterface $extension
     * @throws ContainerInvocationException
     */
    private function fireBoot(ExtensionInterface $extension): void
    {
        $this->booted[] = \get_class($extension);

        $method = [$extension, self::EXTENSION_BOOT_METHOD];

        if (\method_exists(...$method)) {
            $this->app->call($method);
        }
    }
}
