<?php

declare(strict_types=1);

namespace Railt\Http;

use Railt\Contracts\Http\RequestInterface;

interface MutableRequestInterface extends RequestInterface
{
    /**
     * Mutable equivalent of {@see QueryProviderInterface::withQuery()} method.
     *
     * @link QueryProviderInterface::withQuery() method description.
     */
    public function setQuery(string $query): void;

    /**
     * Mutable equivalent of {@see VariablesProviderInterface::withAddedVariable()} method.
     *
     * @link VariablesProviderInterface::withAddedVariable() method description.
     *
     * @param non-empty-string $name
     */
    public function setVariable(string $name, mixed $value): void;

    /**
     * Mutable equivalent of {@see VariablesProviderInterface::withVariables()} method.
     *
     * @link VariablesProviderInterface::withVariables() method description.
     *
     * @param iterable<non-empty-string, mixed> $variables
     */
    public function setVariables(iterable $variables): void;

    /**
     * Mutable equivalent of {@see VariablesProviderInterface::withoutVariable()} method.
     *
     * @link VariablesProviderInterface::withoutVariable() method description.
     *
     * @param non-empty-string $name
     */
    public function removeVariable(string $name): void;

    /**
     * Mutable equivalent of {@see OperationNameProviderInterface::withOperationName()} method.
     *
     * @link OperationNameProviderInterface::withOperationName() method description.
     *
     * @param non-empty-string $name
     */
    public function setOperationName(string $name): void;

    /**
     * Mutable equivalent of {@see OperationNameProviderInterface::withoutOperationName()} method.
     *
     * @link OperationNameProviderInterface::withoutOperationName() method description.
     */
    public function removeOperationName(): void;
}
