<?php

declare(strict_types=1);

namespace Railt\Bridge\Webonyx;

use GraphQL\Executor\ExecutionResult;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use Railt\Bridge\Webonyx\Executor\ErrorBuilder;
use Railt\Contracts\Http\ErrorInterface;
use Railt\Contracts\Http\Factory\ErrorFactoryInterface;
use Railt\Contracts\Http\Factory\ResponseFactoryInterface;
use Railt\Contracts\Http\Middleware\RequestHandlerInterface;
use Railt\Contracts\Http\RequestInterface;
use Railt\Contracts\Http\ResponseInterface;
use Railt\Foundation\ConnectionInterface;

final class WebonyxRequestHandler implements RequestHandlerInterface
{
    private const ERROR_MESSAGE_EMPTY_REQUEST
        = 'GraphQL request must contain a valid query data, but it came empty'
    ;

    private readonly ErrorBuilder $errorsBuilder;

    public function __construct(
        private readonly Schema $schema,
        private readonly ConnectionInterface $connection,
        private readonly ResponseFactoryInterface $responses,
        private readonly ErrorFactoryInterface $errors,
    ) {
        $this->errorsBuilder = new ErrorBuilder($errors);
    }

    private function emptyRequestError(): ErrorInterface
    {
        return $this->errors->createError(self::ERROR_MESSAGE_EMPTY_REQUEST)
            ->withCategory($this->errors->createClientErrorCategory())
        ;
    }

    public function handle(RequestInterface $request): ResponseInterface
    {
        if ($request->isEmpty()) {
            return $this->responses->createResponse(exceptions: [
                $this->emptyRequestError(),
            ]);
        }

        $result = GraphQL::executeQuery(
            schema: $this->schema,
            source: $request->getQuery(),
            rootValue: $rootValue = null,
            contextValue: $context = null,
            variableValues: $request->getVariables(),
            operationName: $request->getOperationName(),
            fieldResolver: $fieldResolver = null,
            validationRules: $validationRules = null
        );

        return $this->createResponse($result);
    }

    private function createResponse(ExecutionResult $result): ResponseInterface
    {
        $response = $this->responses->createResponse($result->data);

        try {
            foreach ($result->errors as $error) {
                $response = $response->withAddedException(
                    $this->errorsBuilder->create($error),
                );
            }
        } catch (\Throwable $e) {
            $response = $response->withAddedException($e);
        }

        return $response;
    }
}
