<?php

declare(strict_types=1);

namespace Railt\Http\Factory;

use Railt\Contracts\Http\Factory\ErrorFactoryInterface;
use Railt\Http\Exception\Category;
use Railt\Http\Exception\Location;
use Railt\Http\GraphQLError;

final class GraphQLErrorFactory implements ErrorFactoryInterface
{
    public function createError(string $message, int $code = 0, \Throwable $prev = null): GraphQLError
    {
        return new GraphQLError($message, $code, $prev, $this->createInternalErrorCategory());
    }

    public function createInternalErrorCategory(): Category
    {
        return Category::INTERNAL;
    }

    public function createClientErrorCategory(): Category
    {
        return Category::QUERY;
    }

    public function createErrorLocation(int $line = 1, int $column = 1): Location
    {
        return new Location($line, $column);
    }
}
