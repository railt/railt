<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Webonyx\Executor;

use GraphQL\Executor\ExecutionResult;
use Railt\Component\Http\Response;
use Railt\Component\Http\ResponseInterface;

/**
 * Class ResponseResolver
 */
class ResponseResolver
{
    /**
     * @param ExecutionResult $result
     * @return ResponseInterface
     */
    public static function resolve(ExecutionResult $result): ResponseInterface
    {
        $response = new Response($result->data);

        try {
            foreach ($result->errors as $exception) {
                $response->withException(ExceptionResolver::resolve($exception));
            }
        } catch (\Throwable $e) {
            $response->withException($e);
        }

        foreach ((array)$result->extensions as $name => $value) {
            $response->withExtension($name, $value);
        }

        return $response;
    }
}
