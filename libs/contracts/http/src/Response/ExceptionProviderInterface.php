<?php

declare(strict_types=1);

namespace Railt\Contracts\Http\Response;

use Railt\Contracts\Http\ErrorInterface;

interface ExceptionProviderInterface
{
    /**
     * Returns {@see true} in case of exceptions list is empty
     * or {@see false} instead.
     */
    public function isSuccessful(): bool;

    /**
     * Returns mapped from exceptions GraphQL errors list.
     *
     * @return iterable<ErrorInterface>
     */
    public function getErrors(): iterable;

    /**
     * Returns list of original response exceptions.
     *
     * @return iterable<\Throwable>
     */
    public function getExceptions(): iterable;

    /**
     * Returns new instance of {@see ExceptionProviderInterface} with the passed
     * exception instances list.
     *
     * @psalm-immutable This method MUST retain the state of the current
     *                  instance, and return an instance that contains the
     *                  specified exception instances.
     *
     * @param iterable<\Throwable> $exceptions
     */
    public function withExceptions(iterable $exceptions): self;

    /**
     * Returns new instance of {@see ExceptionProviderInterface} with the passed
     * exception instance.
     *
     * @psalm-immutable This method MUST retain the state of the current
     *                  instance, and return an instance that contains the
     *                  specified exception instance.
     */
    public function withAddedException(\Throwable $exception): self;

    /**
     * Returns new instance of {@see ExceptionProviderInterface} without passed
     * exception instance.
     *
     * @psalm-immutable This method MUST retain the state of the current
     *                  instance, and return an instance that not contains
     *                  the specified exception instance.
     */
    public function withoutException(\Throwable $exception): self;
}
