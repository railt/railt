<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Extension;

use Railt\Component\Container\ContainerInterface;
use Railt\Component\Container\Exception\ContainerInvocationException;
use Railt\Component\Container\Exception\ContainerResolutionException;
use Railt\Foundation\Application;
use Railt\Foundation\Application\EnvironmentInterface;
use Railt\Foundation\ApplicationInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class Extension
 * @method void register()
 * @method void boot()
 */
abstract class Extension implements ExtensionInterface
{
    /**
     * @var ApplicationInterface|Application
     */
    protected $app;

    /**
     * @var EventDispatcherInterface|null
     */
    private $events;

    /**
     * @var EnvironmentInterface
     */
    protected $env;

    /**
     * Extension constructor.
     *
     * @param ApplicationInterface $app
     * @param EnvironmentInterface $env
     */
    public function __construct(ApplicationInterface $app, EnvironmentInterface $env)
    {
        $this->app = $app;
        $this->env = $env;
    }

    /**
     * @return EventDispatcherInterface
     * @throws ContainerResolutionException
     * @throws ContainerInvocationException
     */
    protected function events(): EventDispatcherInterface
    {
        if ($this->events === null) {
            $this->events = $this->app->make(EventDispatcherInterface::class);
        }

        return $this->events;
    }

    /**
     * @param string $event
     * @param \Closure $then
     * @param int $priority
     * @return Extension|$this
     * @throws ContainerResolutionException
     * @throws ContainerInvocationException
     */
    protected function on(string $event, \Closure $then, int $priority = 0): self
    {
        $this->events()->addListener($event, $then, $priority);

        return $this;
    }

    /**
     * @param EventSubscriberInterface $subscriber
     * @return Extension|$this
     * @throws ContainerResolutionException
     * @throws ContainerInvocationException
     */
    protected function subscribe(EventSubscriberInterface $subscriber): self
    {
        $this->events()->addSubscriber($subscriber);

        return $this;
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
     * @param string $name
     * @param \Closure $registrar
     * @return Extension|$this
     */
    protected function registerIfNotRegistered(string $name, \Closure $registrar): self
    {
        $this->app->registerIfNotRegistered($name, $registrar);

        return $this;
    }

    /**
     * @param string $locator
     * @param array $params
     * @return mixed|object
     * @throws ContainerResolutionException
     * @throws ContainerInvocationException
     */
    protected function make(string $locator, array $params = [])
    {
        return $this->app->make($locator, $params);
    }
}
