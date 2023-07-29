<?php

declare(strict_types=1);

namespace Railt\Contracts\Http\Request;

interface VariablesProviderInterface
{
    /**
     * @return array<non-empty-string, mixed>
     */
    public function getVariables(): array;

    /**
     * Returns new instance of {@see VariablesProviderInterface} with the
     * passed variables.
     *
     * @psalm-immutable This method MUST retain the state of the current
     *                  instance, and return an instance that contains the
     *                  variables.
     *
     * @param iterable<non-empty-string, mixed> $variables
     */
    public function withVariables(iterable $variables): self;

    /**
     * Returns new instance of {@see VariablesProviderInterface} with the
     * passed variable item.
     *
     * @psalm-immutable This method MUST retain the state of the current
     *                  instance, and return an instance that contains the
     *                  variable item.
     *
     * @param non-empty-string $name
     */
    public function withAddedVariable(string $name, mixed $value): self;

    /**
     * Returns new instance of {@see VariablesProviderInterface} without the
     * variable item.
     *
     * @psalm-immutable This method MUST retain the state of the current
     *                  instance, and return an instance that not contains
     *                  the variable item.
     *
     * @param non-empty-string $name
     */
    public function withoutVariable(string $name): self;

    /**
     * @template TResult of mixed
     *
     * @param non-empty-string $name
     * @param TResult $default
     * @return TResult|mixed
     */
    public function getVariable(string $name, mixed $default = null): mixed;

    /**
     * @param non-empty-string $name
     */
    public function hasVariable(string $name): bool;
}
