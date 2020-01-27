<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL;

use GraphQL\Contracts\TypeSystem\SchemaInterface;
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
     * @param array $variables
     * @return CompilerInterface|$this
     */
    public function preload($source, array $variables = []): self;

    /**
     * Compiles the sources and all previously loaded types
     * into the final document.
     *
     * @param ReadableInterface|string|resource|mixed $source
     * @param array $variables
     * @return SchemaInterface
     */
    public function compile($source, array $variables = []): SchemaInterface;
}
