<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Event;

use Railt\Foundation\Application;
use Railt\Foundation\Event\Subscribers\DefaultResponseSubscriber;
use Railt\Foundation\Event\Subscribers\InputParentSubscriber;
use Railt\Foundation\Event\Subscribers\InputSubscriber;
use Railt\Foundation\Event\Subscribers\TypeResolveSubscriber;
use Railt\Foundation\Extension\Extension;
use Railt\Foundation\Extension\Status;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class EventsExtension
 */
class EventsExtension extends Extension
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Events';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Provides application mediator system (EventDispatcher).';
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
        $this->app->registerIfNotRegistered(EventDispatcherInterface::class, function () {
            return new EventDispatcher();
        });

        $this->app->alias(EventDispatcherInterface::class, EventDispatcher::class);
    }

    /**
     * @param EventDispatcherInterface $events
     */
    public function boot(EventDispatcherInterface $events): void
    {
        //
        // Provides access to all inputs within the system.
        //
        $events->addSubscriber($inputs = new InputSubscriber());

        //
        // Adds to Input types the ability to refer to the parent chain.
        //
        $events->addSubscriber(new InputParentSubscriber($inputs));

        //
        // Basic type resolving logic implementation.
        //
        $events->addSubscriber(new TypeResolveSubscriber());

        //
        // Resolving response from parent value
        //
        $events->addSubscriber(new DefaultResponseSubscriber());
    }
}
