<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Services;

use Railt\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class EventsService
 */
class EventsService implements Service
{
    /**
     * @param ContainerInterface $container
     * @param bool $debug
     */
    public function register(ContainerInterface $container, bool $debug): void
    {
        if (! $container->has(EventDispatcherInterface::class)) {
            $container->instance(EventDispatcherInterface::class, new EventDispatcher());
            $container->alias(EventDispatcherInterface::class, EventDispatcher::class);
        }
    }
}
