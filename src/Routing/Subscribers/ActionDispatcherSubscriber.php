<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing\Subscribers;

use Railt\Container\ContainerInterface;
use Railt\Routing\Events\ActionDispatch;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ActionDispatcherSubscriber
 */
class ActionDispatcherSubscriber implements EventSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * ActionSubscriber constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ActionDispatch::class => ['onHandle'],
        ];
    }

    /**
     * @param ActionDispatch $event
     */
    public function onHandle(ActionDispatch $event): void
    {
        $response = $this->container->call($event->getAction(), $event->getArguments());

        $event->withResponse($response);
    }
}
