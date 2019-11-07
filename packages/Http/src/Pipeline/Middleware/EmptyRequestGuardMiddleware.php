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
 * Class EmptyRequestGuardMiddleware
 */
class EmptyRequestGuardMiddleware implements HttpMiddlewareInterface
{
    /**
     * @var string
     */
    protected const ERROR_EMPTY_QUERY = 'GraphQL request must contain a valid query data, but it came empty';

    /**
     * @param RequestInterface $request
     * @param HandlerInterface $next
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request, HandlerInterface $next): ResponseInterface
    {
        if ($request->isEmpty()) {
            /** @var ResponseInterface $response */
            $response = (new Response())
                ->withClientError(self::ERROR_EMPTY_QUERY);

            return $response;
        }

        return $next->handle($request);
    }
}
