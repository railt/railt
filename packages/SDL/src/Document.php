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
 * Class Document
 */
final class Document implements DocumentInterface
{
    /**
     * @var array|NamedTypeInterface[]
     */
    private array $typeMap = [];

    /**
     * @var array|DirectiveInterface[]
     */
    private array $directives = [];

    /**
     * @var array|ExecutionInterface[]
     */
    private array $executions = [];

    /**
     * @var SchemaInterface|null
     */
    private ?SchemaInterface $schema = null;

    /**
     * {@inheritDoc}
     */
    public function getType(string $name): ?NamedTypeInterface
    {
        return $this->typeMap[$name] ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function hasType(string $name): bool
    {
        return isset($this->typeMap[$name]);
    }

    /**
     * {@inheritDoc}
     */
    public function getDirective(string $name): ?DirectiveInterface
    {
        return $this->directives[$name] ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function hasDirective(string $name): bool
    {
        return isset($this->directives[$name]);
    }

    /**
     * @return iterable|ExecutionInterface[]
     */
    public function getExecutions(): iterable
    {
        return $this->executions;
    }

    /**
     * {@inheritDoc}
     */
    public function getTypes(): iterable
    {
        return $this->typeMap;
    }

    /**
     * {@inheritDoc}
     */
    public function getDirectives(): iterable
    {
        return $this->directives;
    }

    /**
     * {@inheritDoc}
     */
    public function getSchema(): ?SchemaInterface
    {
        return $this->schema;
    }

    /**
     * {@inheritDoc}
     */
    public function addType(NamedTypeInterface $type, bool $overwrite = false): self
    {
        if ($overwrite || ! $this->hasType($type->getName())) {
            $this->typeMap[$type->getName()] = $type;
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function addDirective(DirectiveInterface $directive, bool $overwrite = false): self
    {
        if ($overwrite || ! $this->hasDirective($directive->getName())) {
            $this->directives[$directive->getName()] = $directive;
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setSchema(SchemaInterface $schema, bool $overwrite = false): self
    {
        if ($overwrite || ! $this->getSchema()) {
            $this->schema = $schema;
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function addExecution(ExecutionInterface $execution): self
    {
        $this->executions[] = $execution;

        return $this;
    }
}
