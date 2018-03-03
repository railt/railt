<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Events;

/**
 * The Dispatcher is an elementary event system often
 * used as a mediator within the project.
 */
interface Dispatcher extends Listenable
{
    /**
     * Calling a specific event (subscribers to an event)
     * with a strictly defined name.
     *
     * @param string $event
     * @param mixed $payload
     * @return mixed
     */
    public function dispatch(string $event, $payload);
}
