<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Pipeline\Middleware;

use Railt\Http\Response;
use Railt\Http\RequestInterface;
use Railt\Http\ResponseInterface;
use Railt\Http\Pipeline\MiddlewareInterface;
use Railt\Http\Pipeline\Handler\HandlerInterface;

/**
 * Class ExceptionHandlerMiddleware
 */
class ExceptionHandlerMiddleware implements MiddlewareInterface
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
            return (new Response())->withException($e);
        }
    }
}

