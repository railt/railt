<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend;

use Railt\SDL\Backend\Context\DefinitionContextInterface;
use Railt\SDL\Backend\Runtime\ExecutionInterface;
use Railt\TypeSystem\Reference\TypeReferenceInterface;

/**
 * Class Context
 */
class Context
{
    /**
     * @var TypeReferenceInterface|null
     */
    private ?TypeReferenceInterface $query = null;

    /**
     * @var TypeReferenceInterface|null
     */
    private ?TypeReferenceInterface $mutation = null;

    /**
     * @var TypeReferenceInterface|null
     */
    private ?TypeReferenceInterface $subscription = null;

    /**
     * @var array|DefinitionContextInterface[]
     */
    private array $types = [];

    /**
     * @var array|DefinitionContextInterface[]
     */
    private array $directives = [];

    /**
     * @var array|ExecutionInterface[]
     */
    private array $executions = [];

    /**
     * @param ExecutionInterface $execution
     * @return $this
     */
    public function addExecution(ExecutionInterface $execution): self
    {
        $this->executions[] = $execution;

        return $this;
    }

    /**
     * @param TypeReferenceInterface|null $query
     * @return void
     */
    public function setQuery(?TypeReferenceInterface $query): void
    {
        $this->query = $query;
    }

    /**
     * @param TypeReferenceInterface|null $mutation
     * @return void
     */
    public function setMutation(?TypeReferenceInterface $mutation): void
    {
        $this->mutation = $mutation;
    }

    /**
     * @param TypeReferenceInterface|null $subscription
     * @return void
     */
    public function setSubscription(?TypeReferenceInterface $subscription): void
    {
        $this->subscription = $subscription;
    }

    /**
     * @return TypeReferenceInterface|null
     */
    public function getQuery(): ?TypeReferenceInterface
    {
        return $this->query;
    }

    /**
     * @return TypeReferenceInterface|null
     */
    public function getMutation(): ?TypeReferenceInterface
    {
        return $this->mutation;
    }

    /**
     * @return TypeReferenceInterface|null
     */
    public function getSubscription(): ?TypeReferenceInterface
    {
        return $this->subscription;
    }

    /**
     * @return array|ExecutionInterface[]
     */
    public function getExecutions(): array
    {
        return $this->executions;
    }

    /**
     * @param DefinitionContextInterface $type
     * @return $this
     */
    public function addType(DefinitionContextInterface $type): self
    {
        $this->types[$type->getName()] = $type;

        return $this;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasType(string $name): bool
    {
        return isset($this->types[$name]);
    }

    /**
     * @return array|DefinitionContextInterface[]
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    /**
     * @param string $name
     * @return DefinitionContextInterface|null
     */
    public function getType(string $name): ?DefinitionContextInterface
    {
        return $this->types[$name] ?? null;
    }

    /**
     * @param DefinitionContextInterface $type
     * @return $this
     */
    public function addDirective(DefinitionContextInterface $type): self
    {
        $this->directives[$type->getName()] = $type;

        return $this;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasDirective(string $name): bool
    {
        return isset($this->directives[$name]);
    }

    /**
     * @return array|DefinitionContextInterface[]
     */
    public function getDirectives(): array
    {
        return $this->directives;
    }

    /**
     * @param string $name
     * @return DefinitionContextInterface|null
     */
    public function getDirective(string $name): ?DefinitionContextInterface
    {
        return $this->directives[$name] ?? null;
    }
}
