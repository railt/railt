<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Extension\Debug;

use Clockwork\Clockwork;
use Railt\Container\Exception\ContainerInvocationException;
use Railt\Extension\Debug\Clockwork\PerformanceTimelineSubscriber;
use Railt\Extension\Debug\Clockwork\RailtConfigurationSubscriber;
use Railt\Extension\Debug\Clockwork\RailtContainerSubscriber;
use Railt\Extension\Debug\Clockwork\RailtRequestSubscriber;
use Railt\Extension\Debug\Clockwork\RailtSchemaSubscriber;
use Railt\Extension\Debug\Formatter\PrettyResponseSubscriber;
use Railt\Extension\Debug\MemoryProfiler\MemoryProfilerSubscriber;
use Railt\Foundation\Application;
use Railt\Foundation\Config\RepositoryInterface;
use Railt\Foundation\Event\EventsExtension;
use Railt\Foundation\Extension\Extension;
use Railt\Foundation\Extension\Status;

/**
 * Class DebugExtension
 */
class DebugExtension extends Extension
{
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Debugging and profiling extension';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Debug';
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return Status::STABLE;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return Application::VERSION;
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [EventsExtension::class];
    }

    /**
     * @param RepositoryInterface $config
     * @throws ContainerInvocationException
     * @throws \ReflectionException
     */
    public function register(RepositoryInterface $config): void
    {
        if (! $config->get(RepositoryInterface::KEY_DEBUG, false)) {
            return;
        }

        $this->subscribe(new PrettyResponseSubscriber());
        $this->subscribe(new MemoryProfilerSubscriber());

        if ($this->app->has(Clockwork::class)) {
            $clockwork = $this->app->make(Clockwork::class);

            $this->subscribe(new PerformanceTimelineSubscriber($clockwork));
            $this->subscribe(new RailtContainerSubscriber($clockwork, $this->app));
            $this->subscribe(new RailtConfigurationSubscriber($clockwork, $this->app));
            $this->subscribe(new RailtSchemaSubscriber($clockwork, $this->app));
            $this->subscribe(new RailtRequestSubscriber($clockwork, $this->app));
        }
    }
}
