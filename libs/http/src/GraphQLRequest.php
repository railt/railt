<?php

declare(strict_types=1);

namespace Railt\Http;

use Railt\Contracts\Http\RequestInterface;

class GraphQLRequest implements RequestInterface
{
    /**
     * @var array<non-empty-string, mixed>
     */
    protected array $variables = [];

    /**
     * @param array<non-empty-string, mixed> $variables
     * @param non-empty-string|null $operationName
     */
    public function __construct(
        protected string $query,
        iterable $variables = [],
        protected ?string $operationName = null,
    ) {
        $this->setVariables($variables);
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function withQuery(string $query): self
    {
        $self = clone $this;
        $self->setQuery($query);

        return $self;
    }

    /**
     * Mutable equivalent of {@see RequestInterface::withQuery()} method.
     *
     * @link RequestInterface::withQuery() method description.
     */
    public function setQuery(string $query): void
    {
        $this->query = $query;
    }

    public function isEmpty(): bool
    {
        return \trim($this->query) !== '';
    }

    public function getVariables(): array
    {
        return $this->variables;
    }

    public function withVariables(iterable $variables): self
    {
        $self = clone $this;
        $self->setVariables($variables);

        return $self;
    }

    public function withAddedVariable(string $name, mixed $value): self
    {
        $self = clone $this;
        $self->setVariable($name, $value);

        return $self;
    }

    /**
     * Mutable equivalent of {@see RequestInterface::withVariables()} method.
     *
     * @link RequestInterface::withVariables() method description.
     *
     * @param iterable<non-empty-string, mixed> $variables
     */
    public function setVariables(iterable $variables): void
    {
        /** @psalm-suppress MixedAssignment */
        foreach ($variables as $name => $value) {
            $this->setVariable($name, $value);
        }
    }

    /**
     * Mutable equivalent of {@see RequestInterface::withAddedVariable()} method.
     *
     * @link RequestInterface::withAddedVariable() method description.
     *
     * @param non-empty-string $name
     */
    public function setVariable(string $name, mixed $value): void
    {
        $this->variables[$name] = $value;
    }

    public function getVariable(string $name, mixed $default = null): mixed
    {
        return $this->variables[$name] ?? $default;
    }

    public function hasVariable(string $name): bool
    {
        return isset($this->variables[$name])
            && \array_key_exists($name, $this->variables)
        ;
    }

    public function getOperationName(): ?string
    {
        return $this->operationName;
    }

    public function withOperationName(string $name): self
    {
        $self = clone $this;
        $self->setOperationName($name);

        return $self;
    }

    public function withoutOperationName(): self
    {
        $self = clone $this;
        $self->removeOperationName();

        return $self;
    }

    /**
     * Mutable equivalent of {@see RequestInterface::withOperationName()} method.
     *
     * @link RequestInterface::withOperationName() method description.
     *
     * @param non-empty-string $name
     */
    public function setOperationName(string $name): void
    {
        $this->operationName = $name;
    }

    /**
     * Mutable equivalent of {@see RequestInterface::withoutOperationName()} method.
     *
     * @link RequestInterface::withoutOperationName() method description.
     */
    public function removeOperationName(): void
    {
        $this->operationName = null;
    }

    public function hasOperationName(): bool
    {
        return $this->operationName !== null;
    }
}
