<?php

declare(strict_types=1);

namespace Railt\Contracts\Http\Factory;

use Railt\Contracts\Http\Error\CategoryInterface;
use Railt\Contracts\Http\Error\LocationInterface;
use Railt\Contracts\Http\ErrorInterface;

interface ErrorFactoryInterface
{
    /**
     * Creates a new error instance from the given parameters.
     */
    public function createError(string $message, int $code = 0, \Throwable $prev = null): ErrorInterface;

    /**
     * Returns internal GraphQL error category.
     */
    public function createInternalErrorCategory(): CategoryInterface;

    /**
     * Returns client GraphQL error category.
     */
    public function createClientErrorCategory(): CategoryInterface;

    /**
     * @param int<1, max> $line
     * @param int<1, max> $column
     */
    public function createErrorLocation(int $line = 1, int $column = 1): LocationInterface;
}
