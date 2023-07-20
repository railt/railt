<?php

declare(strict_types=1);

namespace Railt\Contracts\Http\Request;

interface OperationNameProviderInterface
{
    /**
     * Returns the name of the operation to use if request contains multiple
     * possible operations.
     *
     * Can be omitted ({@see null}) if request contains only one operation.
     *
     * @return non-empty-string|null
     */
    public function getOperationName(): ?string;

    /**
     * Returns new instance of {@see OperationNameProviderInterface} with the
     * passed operation name argument.
     *
     * @psalm-immutable This method MUST retain the state of the current
     *                  instance, and return an instance that contains the
     *                  operation name.
     *
     * @param non-empty-string $name
     */
    public function withOperationName(string $name): self;

    /**
     * Returns new instance of {@see OperationNameProviderInterface} without
     * the operation name argument.
     *
     * @psalm-immutable This method MUST retain the state of the current
     *                  instance, and return an instance that does not contain
     *                  the operation name.
     */
    public function withoutOperationName(): self;

    /**
     * Returns {@see true} in case of operation name is not {@see null}
     * or {@see false} instead.
     */
    public function hasOperationName(): bool;
}
