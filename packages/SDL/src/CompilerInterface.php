<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL;

use Phplrt\Contracts\Source\ReadableInterface;

/**
 * Interface CompilerInterface
 */
interface CompilerInterface
{
    /**
     * Loads GraphQL source into the compiler.
     *
     * @param ReadableInterface|string|resource|mixed $source
     * @return CompilerInterface|$this
     */
    public function preload($source): self;

    /**
     * Compiles the sources and all previously loaded types
     * into the final document.
     *
     * @param ReadableInterface|string|resource|mixed $source
     * @return DocumentInterface
     */
    public function compile($source): DocumentInterface;

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
