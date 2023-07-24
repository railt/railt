<?php

declare(strict_types=1);

namespace Railt\Bridge\Webonyx;

use Railt\Contracts\Http\Factory\ErrorFactoryInterface;
use Railt\Contracts\Http\Factory\ResponseFactoryInterface;
use Railt\Contracts\Http\Middleware\RequestHandlerInterface;
use Railt\Foundation\ConnectionInterface;
use Railt\Foundation\ExecutorInterface;
use Railt\Http\Factory\GraphQLErrorFactory;
use Railt\Http\Factory\GraphQLResponseFactory;
use Railt\SDL\DictionaryInterface;

final class WebonyxExecutor implements ExecutorInterface
{
    public function __construct(
        private readonly ResponseFactoryInterface $responses = new GraphQLResponseFactory(),
        private readonly ErrorFactoryInterface $errors = new GraphQLErrorFactory(),
        private readonly FactoryInterface $factory = new Factory(),
    ) {
    }

    public function load(
        DictionaryInterface $types,
        ConnectionInterface $connection,
    ): RequestHandlerInterface {
        return new WebonyxRequestHandler(
            schema: $this->factory->build($types),
            connection: $connection,
            responses: $this->responses,
            errors: $this->errors,
        );
    }
}
