<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL;

use Railt\SDL\Runtime\Type\ExecutionInterface;
use GraphQL\Contracts\TypeSystem\SchemaInterface;
use GraphQL\Contracts\TypeSystem\DirectiveInterface;
use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;

/**
 * Interface DocumentInterface
 */
interface DocumentInterface
{
    /**
     * @return iterable|NamedTypeInterface[]
     */
    public function getTypes(): iterable;

    /**
     * @param string $name
     * @return NamedTypeInterface|null
     */
    public function getType(string $name): ?NamedTypeInterface;

    /**
     * @param string $name
     * @return bool
     */
    public function hasType(string $name): bool;

    /**
     * Adds a compiled GraphQL type to the dictionary.
     *
     * @param NamedTypeInterface $type
     * @param bool $overwrite
     * @return DocumentInterface|$this
     */
    public function addType(NamedTypeInterface $type, bool $overwrite = false): self;

    /**
     * @return iterable|DirectiveInterface[]
     */
    public function getDirectives(): iterable;

    /**
     * @param string $name
     * @return DirectiveInterface|null
     */
    public function getDirective(string $name): ?DirectiveInterface;

    /**
     * @param string $name
     * @return bool
     */
    public function hasDirective(string $name): bool;

    /**
     * Adds a compiled GraphQL directive to the dictionary.
     *
     * @param DirectiveInterface $type
     * @param bool $overwrite
     * @return DocumentInterface|$this
     */
    public function addDirective(DirectiveInterface $type, bool $overwrite = false): self;

    /**
     * @return SchemaInterface|null
     */
    public function getSchema(): ?SchemaInterface;

    /**
     * Adds a compiled GraphQL schema to the dictionary.
     *
     * @param SchemaInterface $type
     * @param bool $overwrite
     * @return DocumentInterface|$this
     */
    public function setSchema(SchemaInterface $type, bool $overwrite = false): self;

    /**
     * @return iterable|ExecutionInterface[]
     */
    public function getExecutions(): iterable;

    /**
     * @param ExecutionInterface $execution
     * @return DocumentInterface|$this
     */
    public function addExecution(ExecutionInterface $execution): self;
}
