<?php

declare(strict_types=1);

namespace Railt\Foundation\Extension;

use Railt\EventDispatcher\EventDispatcherInterface;
use Railt\Foundation\ConnectionInterface;

/**
 * @template TContext of object
 */
interface ExtensionInterface
{
    /**
     * Loads an extension for the specified event dispatcher.
     *
     * Should return a context object ({@see TContext}) that is associated with
     * the current {@see ConnectionInterface} and will be removed from memory
     * after the connection is closed.
     *
     * @return TContext
     */
    public function load(EventDispatcherInterface $dispatcher): object;

    /**
     * The method is called when the {@see ConnectionInterface} is closed,
     * where the previously loaded from {@see load()} method context is passed.
     *
     * @param TContext $context
     */
    public function unload(object $context): void;
}
