<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL;

use GraphQL\GraphQL;
use Railt\Http\Response;
use GraphQL\Error\Error;
use GraphQL\Type\Schema;
use Railt\Http\GraphQLError;
use GraphQL\Executor\ExecutionResult;
use Railt\Contracts\Http\RequestInterface;
use Railt\Contracts\Http\ResponseInterface;
use Railt\Contracts\Http\GraphQLErrorInterface;

/**
 * Class WebonyxExecutor
 */
class WebonyxExecutorHandler extends ExecutorHandler
{
    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request): ResponseInterface
    {
        $result = $this->run($request);

        return new Response($result->data, $this->errors($result), $result->extensions);
    }

    /**
     * @param ExecutionResult $result
     * @return array
     */
    protected function errors(ExecutionResult $result): array
    {
        $errors = [];

        foreach ($result->errors as $error) {
            if ($error instanceof Error) {
                $error = $this->transformError($error);
            }

            $errors[] = $error;
        }

        return $errors;
    }

    /**
     * @param Error $error
     * @return GraphQLErrorInterface
     */
    protected function transformError(Error $error): GraphQLErrorInterface
    {
        $exception = new GraphQLError($error->message, $error->getCode(), $error->getPrevious());

        if ($error->isClientSafe()) {
            $exception->publish();
        }

        return $exception;
    }

    /**
     * @param RequestInterface $request
     * @return ExecutionResult
     */
    private function run(RequestInterface $request): ExecutionResult
    {
        return GraphQL::executeQuery(
            new Schema($this->getSchema()->toArray()),
            $request->getQuery(),
            null,
            $this->context($request),
            $request->getVariables(),
            $request->getOperationName()
        );
    }
}
