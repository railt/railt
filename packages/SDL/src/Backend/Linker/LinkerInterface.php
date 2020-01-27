<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Linker;

use Railt\SDL\CompilerInterface;

/**
 * Interface LinkerInterface
 */
interface LinkerInterface
{
    /**
     * Adds an interceptor of events of the types linker and
     * allows loading the missing type.
     *
     * @param callable $loader
     * @return CompilerInterface|$this
     */
    public function autoload(callable $loader): self;

    /**
     * Removes a previously registered linker interceptor.
     *
     * @param callable $loader
     * @return CompilerInterface|$this
     */
    public function cancelAutoload(callable $loader): self;

    /**
     * Returns list of registered loaders
     *
     * @return iterable|callable[]
     */
    public function getAutoloaders(): iterable;
}
