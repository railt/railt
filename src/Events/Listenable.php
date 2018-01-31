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
 * Interface Listenable
 */
interface Listenable
{
    /**
     * Subscribe to a specific event type. The `*` symbol
     * means any text in the event name.
     *
     * If the "\Closure" returns something other than `null` (void),
     * then further subscribers will not be called. That is, if two
     * listeners (subscribers) were created and the closure of the first
     * returns, for example, falsity, then the second subscriber will
     * not be called.
     *
     * @param string $event
     * @param \Closure $callback
     * @return Listenable
     */
    public function listen(string $event, \Closure $callback): Listenable;
}
