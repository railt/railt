<?php

declare(strict_types=1);

namespace Railt\Contracts\Http;

use Railt\Contracts\Http\Input\ArgumentsProviderInterface;
use Railt\Contracts\Http\Input\PathProviderInterface;
use Railt\Contracts\Http\Input\SelectionProviderInterface;

/**
 * @template TDefinition of object
 */
interface InputInterface extends
    PathProviderInterface,
    ArgumentsProviderInterface,
    SelectionProviderInterface
{
    /**
     * Returns the GraphQL {@see RequestInterface} instance associated with
     * the given input payload.
     */
    public function getRequest(): RequestInterface;

    /**
     * Returns GraphQL query field name.
     *
     * @return non-empty-string
     */
    public function getFieldName(): string;

    /**
     * Returns GraphQL query field alias if it is or {@see null} instead.
     *
     * @return non-empty-string|null
     */
    public function getFieldAlias(): ?string;

    /**
     * Returns an instance of the field definition.
     *
     * @return TDefinition
     */
    public function getFieldDefinition(): object;

    /**
     * Returns result of the parent value.
     *
     * @return mixed
     */
    public function getParentValue(): mixed;
}
