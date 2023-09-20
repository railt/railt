<?php

declare(strict_types=1);

namespace Railt\Foundation\Extension;

/**
 * @template-implements \Traversable<array-key, ExtensionInterface>
 */
interface RepositoryInterface extends ExtensionInterface, \Traversable, \Countable
{
    /**
     * @param ExtensionInterface $extension
     */
    public function register(ExtensionInterface $extension): void;
}
