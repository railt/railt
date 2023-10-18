<?php

declare(strict_types=1);

namespace Railt\Foundation\Extension;

/**
 * @template TContext of object
 *
 * @template-extends \Traversable<array-key, ExtensionInterface>
 * @template-extends ExtensionInterface<TContext>
 */
interface RepositoryInterface extends ExtensionInterface, \Traversable, \Countable
{
    /**
     * @param ExtensionInterface $extension
     */
    public function register(ExtensionInterface $extension): void;
}
