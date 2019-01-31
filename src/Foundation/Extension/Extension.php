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
use Railt\Container\Exception\ContainerInvocationException;
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
     * @var string
     */
    private const METHOD_REGISTER = 'register';

    /**
     * @var string
     */
    private const METHOD_BOOT = 'boot';

    /**
     * @var ContainerInterface
     */
    protected $app;

    /**
     * @var bool
     */
    private $booted = false;

    /**
     * @var bool
     */
    private $registered = false;

    /**
     * @var EventDispatcherInterface|null
     */
    private $events;

    /**
     * Extension constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->app = $container;

        $this->onRegister();
    }

    /**
     * @return EventDispatcherInterface
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
     */
    protected function on(string $event, \Closure $then, int $priority = 0): self
    {
        $this->events()->addListener($event, $then, $priority);

        return $this;
    }

    /**
     * @param EventSubscriberInterface $subscriber
     * @return Extension|$this
     */
    protected function subscribe(EventSubscriberInterface $subscriber): self
    {
        $this->events()->addSubscriber($subscriber);

        return $this;
    }

    /**
     * @return void
     */
    private function onRegister(): void
    {
        if (! $this->registered && \method_exists($this, self::METHOD_REGISTER)) {
            $this->registered = true;

            $this->app->call(\Closure::fromCallable([$this, self::METHOD_REGISTER]));
        }
    }

    /**
     * @return void
     */
    public function run(): void
    {
        if (! $this->booted && \method_exists($this, self::METHOD_BOOT)) {
            $this->booted = true;

            $this->app->call(\Closure::fromCallable([$this, self::METHOD_BOOT]));
        }
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
     * @throws ContainerInvocationException
     */
    protected function registerIfNotRegistered(string $name, \Closure $registrar): self
    {
        if (! $this->app->has($name) && $instance = $this->app->call($registrar)) {
            $this->app->instance($name, $instance);

            if ($name !== \get_class($instance)) {
                $this->app->alias($name, \get_class($instance));
            }
        }

        return $this;
    }

    /**
     * @param string $locator
     * @param array $params
     * @return mixed|object
     */
    protected function make(string $locator, array $params = [])
    {
        return $this->app->make($locator, $params);
    }
}
