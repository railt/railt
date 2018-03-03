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
use Railt\Events\Dispatcher;
use Railt\Events\Events;

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
        if (! $container->has(Dispatcher::class)) {
            $container->instance(Dispatcher::class, new Events());
            $container->alias(Dispatcher::class, Events::class);
        }
    }
}
