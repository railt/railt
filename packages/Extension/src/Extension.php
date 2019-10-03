<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Extension;

use Railt\Container\Container;
use Railt\Container\ContainerInterface;
use Railt\Container\Exception\ContainerResolutionException;

/**
 * Class Extension
 *
 * @method void register()
 * @method void boot()
 */
abstract class Extension implements ExtensionInterface
{
    /**
     * @var ContainerInterface
     */
    protected ContainerInterface $app;

    /**
     * Extension constructor.
     *
     * @param ContainerInterface $app
     */
    public function __construct(ContainerInterface $app)
    {
        $this->app = $app;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->getName() . ' extension';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return \class_basename(static::class);
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return '1.0.0';
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return Status::EXPERIMENTAL;
    }

    /**
     * @return array|string[]
     */
    public function getDependencies(): array
    {
        return [];
    }

    /**
     * @return ContainerInterface
     */
    public function app(): ContainerInterface
    {
        return $this->app;
    }

    /**
     * @param string $locator
     * @param array $params
     * @return mixed|object
     * @throws ContainerResolutionException
     */
    protected function make(string $locator, array $params = [])
    {
        return $this->app->make($locator, $params);
    }

    /**
     * @param string $locator
     * @param \Closure $then
     * @return void
     */
    protected function registerIfNotRegistered(string $locator, \Closure $then): void
    {
        if ($this->app instanceof Container) {
            $this->app->registerIfNotRegistered($locator, $then);

            return;
        }

        if (! $this->app->has($locator)) {
            $this->app->register($locator, $then);
        }
    }
}
