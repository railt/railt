<?php

declare(strict_types=1);

namespace Railt\Executor\Webonyx;

use Psr\EventDispatcher\EventDispatcherInterface;
use Railt\Contracts\Http\Factory\ErrorFactoryInterface;
use Railt\Contracts\Http\Factory\ResponseFactoryInterface;
use Railt\Contracts\Http\Middleware\RequestHandlerInterface;
use Railt\Foundation\ConnectionInterface;
use Railt\Foundation\ExecutorInterface;
use Railt\Http\Factory\GraphQLErrorFactory;
use Railt\Http\Factory\GraphQLResponseFactory;
use Railt\TypeSystem\DictionaryInterface;

final class WebonyxExecutor implements ExecutorInterface
{
    public function __construct(
        private readonly ResponseFactoryInterface $responses = new GraphQLResponseFactory(),
        private readonly ErrorFactoryInterface $errors = new GraphQLErrorFactory(),
        private readonly FactoryInterface $factory = new Factory(),
        private readonly bool $debug = false,
    ) {
    }

    public function load(
        ConnectionInterface $connection,
        DictionaryInterface $types,
        EventDispatcherInterface $dispatcher,
    ): RequestHandlerInterface {
        return new WebonyxRequestHandler(
            schema: $this->factory->build($types),
            connection: $connection,
            responses: $this->responses,
            errors: $this->errors,
            dispatcher: $dispatcher,
            debug: $this->debug,
        );
    }
}
