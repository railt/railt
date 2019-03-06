<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Debug;

use Clockwork\Clockwork;
use Railt\Container\Exception\ContainerResolutionException;
use Railt\Debug\Clockwork\ApplicationUserDataSubscriber;
use Railt\Debug\Clockwork\ConnectionTimelineSubscriber;
use Railt\Debug\Clockwork\FieldResolveTimelineSubscriber;
use Railt\Debug\Clockwork\HttpLifecycleUserDataSubscriber;
use Railt\Debug\Clockwork\RequestTimelineSubscriber;
use Railt\Debug\Formatter\PrettyResponseSubscriber;
use Railt\Debug\MemoryProfiler\MemoryProfilerSubscriber;
use Railt\Debug\PrismaTracing\PrismaTracingSubscriber;
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
     */
    public function register(RepositoryInterface $config): void
    {
        if (! $config->get(RepositoryInterface::KEY_DEBUG, false)) {
            return;
        }
    }

    /**
     * @param RepositoryInterface $config
     * @throws ContainerResolutionException
     * @throws \Railt\Container\Exception\ContainerInvocationException
     * @throws \ReflectionException
     */
    public function boot(RepositoryInterface $config): void
    {
        if (! $config->get(RepositoryInterface::KEY_DEBUG, false)) {
            return;
        }


        $this->subscribe(new PrettyResponseSubscriber());
        $this->subscribe(new PrismaTracingSubscriber());
        $this->subscribe(new MemoryProfilerSubscriber());


        if ($this->app->has(Clockwork::class)) {
            $clockwork = $this->app->make(Clockwork::class);

            $this->subscribe(new ConnectionTimelineSubscriber($clockwork));
            $this->subscribe(new RequestTimelineSubscriber($clockwork));
            $this->subscribe(new FieldResolveTimelineSubscriber($clockwork));

            $this->subscribe(new ApplicationUserDataSubscriber($clockwork, $this->app));
            $this->subscribe(new HttpLifecycleUserDataSubscriber($clockwork, $this->app));
        }
    }
}
