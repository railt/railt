<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Pipeline\Middleware;

use Railt\Contracts\Http\RequestInterface;
use Railt\Contracts\Http\ResponseInterface;
use Railt\Contracts\Pipeline\Http\HandlerInterface;
use Railt\Contracts\Pipeline\Http\HttpMiddlewareInterface;
use Railt\Http\Response;

/**
 * Class ExceptionHandlerMiddleware
 */
class ExceptionHandlerMiddleware implements HttpMiddlewareInterface
{
    /**
     * @param RequestInterface $request
     * @param HandlerInterface $next
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request, HandlerInterface $next): ResponseInterface
    {
        try {
            return $next->handle($request);
        } catch (\Throwable $e) {
            $response = new Response();
            $response = $response->withException($e);

            /** @var ResponseInterface $response */
            return $response;
        }
    }
}
