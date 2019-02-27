<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Extension;

use Railt\Container\Exception\ContainerInvocationException;
use Railt\Container\Exception\ContainerResolutionException;
use Railt\Container\Exception\ParameterResolutionException;
use Railt\Foundation\ApplicationInterface;
use Railt\Foundation\Exception\ExtensionException;

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
     * @var array|ExtensionInterface[]
     */
    private $extensions = [];

    /**
     * @var ApplicationInterface
     */
    private $app;

    /**
     * @var array|string[]
     */
    private $booted = [];

    /**
     * Repository constructor.
     *
     * @param ApplicationInterface $app
     */
    public function __construct(ApplicationInterface $app)
    {
        $this->app = $app;
    }

    /**
     * @param string $extension
     * @return void
     * @throws ExtensionException
     * @throws ParameterResolutionException
     * @throws ContainerInvocationException
     * @throws ContainerResolutionException
     */
    public function add(string $extension): void
    {
        if (isset($this->extensions[$extension])) {
            return;
        }

        //
        // Register an extension
        //
        $instance = \tap($this->create($extension), function (ExtensionInterface $instance) use ($extension): void {
            $this->extensions[$extension] = $instance;
        });

        $this->loadDependencies($instance);
        $this->fireRegistration($instance);
    }

    /**
     * @param string $extension
     * @return ExtensionInterface
     * @throws ContainerResolutionException
     */
    private function create(string $extension): ExtensionInterface
    {
        try {
            return $this->app->make($extension);
        } catch (ParameterResolutionException $e) {
            throw $e;
        } catch (ContainerResolutionException $e) {
            $error = \sprintf('Could not initialize an extension %s', $extension);
            throw new ExtensionException($error, 0, $e);
        }
    }

    /**
     * @param ExtensionInterface $extension
     * @throws ContainerInvocationException
     * @throws ParameterResolutionException
     * @throws ContainerResolutionException
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

        $message = 'Could not load extension "%s" from [%s %s].';
        $message .= \is_string($package)
            ? \sprintf('You need to require the project dependency "%s" using Composer', $package)
            : \sprintf('Class %s not found or could not be loaded', $dependency);

        return new ExtensionException(\sprintf($message, $dependency, $from->getName(), $from->getVersion()));
    }

    /**
     * @param string|ExtensionInterface $extension
     * @return bool
     */
    private function isRegistered($extension): bool
    {
        \assert(\is_object($extension) || \is_string($extension));

        $extension = \is_object($extension) ? \get_class($extension) : $extension;

        return isset($this->extensions[$extension]);
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
     * @return iterable|ExtensionInterface[]
     */
    public function all(): iterable
    {
        return $this->extensions;
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
    private function isBooted($extension): bool
    {
        \assert(\is_object($extension) || \is_string($extension));

        $extension = \is_object($extension) ? \get_class($extension) : $extension;

        return isset($this->booted[$extension]);
    }

    /**
     * @param ExtensionInterface $extension
     * @throws ContainerInvocationException
     */
    private function fireBoot(ExtensionInterface $extension): void
    {
        $method = [$extension, self::EXTENSION_BOOT_METHOD];

        if (\method_exists(...$method)) {
            $this->app->call($method);
        }
    }
}
