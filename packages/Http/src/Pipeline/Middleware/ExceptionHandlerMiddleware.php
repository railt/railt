<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Pipeline\Middleware;

use Railt\Http\Pipeline\RequestHandlerInterface;
use Railt\Http\Pipeline\RequestMiddlewareInterface;
use Railt\Http\RequestInterface;
use Railt\Http\Response;
use Railt\Http\ResponseInterface;

/**
 * Class ExceptionHandlerMiddleware
 */
class ExceptionHandlerMiddleware implements RequestMiddlewareInterface
{
    /**
     * @param RequestInterface $request
     * @param RequestHandlerInterface $next
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request, RequestHandlerInterface $next): ResponseInterface
    {
        try {
            return $next->handle($request);
        } catch (\Throwable $e) {
            return (new Response())->withException($e);
        }
    }
}
