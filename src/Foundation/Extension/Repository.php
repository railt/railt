<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Extension;

use Railt\Container\ContainerInterface;
use Railt\Container\Exception\ContainerResolutionException;
use Railt\Foundation\Exception\ExtensionException;

/**
 * Class Repository
 */
class Repository
{
    /**
     * @var array|ExtensionInterface[]
     */
    private $extensions = [];

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var array|string[]
     */
    private $booted = [];

    /**
     * Repository constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $extension
     * @return void
     * @throws \Railt\Foundation\Exception\ExtensionException
     */
    public function add(string $extension): void
    {
        /** @noinspection NotOptimalIfConditionsInspection */
        if (isset($this->extensions[$extension]) || \array_key_exists($extension, $this->extensions)) {
            return;
        }

        $this->extensions[$extension] = $this->instance($extension);
    }

    /**
     * @param string $extension
     * @return mixed|object
     * @throws \Railt\Foundation\Exception\ExtensionException
     */
    private function instance(string $extension)
    {
        try {
            return $this->container->make($extension);
        } catch (ContainerResolutionException $e) {
            $error = \sprintf('Could not initialize extension %s', $extension);
            throw new ExtensionException($error, 0, $e);
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
     * @return void
     * @throws ExtensionException
     */
    public function boot(): void
    {
        foreach ($this->extensions as $extension) {
            $this->bootIfNotBooted($extension);
        }
    }

    /**
     * @param ExtensionInterface $extension
     * @throws ExtensionException
     */
    private function bootIfNotBooted(ExtensionInterface $extension): void
    {
        $class = \get_class($extension);

        if (! $this->booted($class)) {
            $this->booted[] = $class;

            $this->loadDependencies($extension);

            $extension->run();
        }
    }

    /**
     * @param string $extension
     * @return bool
     */
    private function booted(string $extension): bool
    {
        return \in_array($extension, $this->booted, true);
    }

    /**
     * @param ExtensionInterface $extension
     * @throws ExtensionException
     */
    private function loadDependencies(ExtensionInterface $extension): void
    {
        foreach ($extension->getDependencies() as $package => $dependencies) {
            foreach ((array)$dependencies as $dependency) {
                $this->loadDependency($extension, $dependency, $package);
            }
        }
    }

    /**
     * @param ExtensionInterface $extension
     * @param string $dependency
     * @param int|string $package
     * @throws ExtensionException
     */
    private function loadDependency(ExtensionInterface $extension, string $dependency, $package): void
    {
        if (! $this->booted($dependency)) {
            if (! \class_exists($dependency)) {
                throw $this->invalidDependency($extension, $dependency, $package);
            }

            $this->add($dependency);
        }
    }

    /**
     * @param ExtensionInterface $extension
     * @param string $dependency
     * @param int|string $package
     * @return ExtensionException
     */
    private function invalidDependency(ExtensionInterface $extension, string $dependency, $package): ExtensionException
    {
        $message = 'Could not include dependent extension "%s" from [%s %s]';
        $message .= \is_string($package)
            ? \sprintf('. You need to set up the project dependency "%s" using Composer.', $package)
            : '';

        return new ExtensionException(\sprintf($message, $dependency, $extension->getName(), $extension->getVersion()));
    }
}
