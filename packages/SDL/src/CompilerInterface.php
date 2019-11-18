<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL;

use GraphQL\Contracts\TypeSystem\DirectiveInterface;
use GraphQL\Contracts\TypeSystem\SchemaInterface;
use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;
use Phplrt\Contracts\Source\ReadableInterface;

/**
 * Interface CompilerInterface
 */
interface CompilerInterface
{
    /**
     * Compiles the sources and all previously loaded types
     * into the final document.
     *
     * @param ReadableInterface|string|resource|mixed $source
     * @return DocumentInterface
     */
    public function compile($source): DocumentInterface;

    /**
     * Loads GraphQL source into the compiler.
     *
     * @param ReadableInterface|string|resource|mixed $source
     * @return CompilerInterface|$this
     */
    public function preload($source): self;

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
     * Adds a compiled GraphQL type to the dictionary.
     *
     * @param NamedTypeInterface $type
     * @param bool $overwrite
     * @return CompilerInterface|$this
     */
    public function withType(NamedTypeInterface $type, bool $overwrite = false): self;

    /**
     * Adds a compiled GraphQL directive to the dictionary.
     *
     * @param DirectiveInterface $type
     * @param bool $overwrite
     * @return CompilerInterface|$this
     */
    public function withDirective(DirectiveInterface $type, bool $overwrite = false): self;

    /**
     * Adds a compiled GraphQL schema to the dictionary.
     *
     * @param SchemaInterface $type
     * @param bool $overwrite
     * @return CompilerInterface|$this
     */
    public function withSchema(SchemaInterface $type, bool $overwrite = false): self;
}
