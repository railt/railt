<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Http\Pipeline\Handler;

use Railt\Contracts\Http\RequestInterface;
use Railt\Contracts\Http\ResponseInterface;
use Railt\Contracts\Pipeline\Http\HandlerInterface;
use Railt\Contracts\Pipeline\Http\HttpMiddlewareInterface;

/**
 * Class Next
 */
class Next implements HandlerInterface
{
    /**
     * @var HandlerInterface
     */
    private HandlerInterface $handler;

    /**
     * @var HttpMiddlewareInterface
     */
    private HttpMiddlewareInterface $middleware;

    /**
     * Next constructor.
     *
     * @param HttpMiddlewareInterface $middleware
     * @param HandlerInterface $handler
     */
    public function __construct(HttpMiddlewareInterface $middleware, HandlerInterface $handler)
    {
        $this->middleware = $middleware;
        $this->handler = $handler;
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request): ResponseInterface
    {
        return $this->middleware->handle($request, $this->handler);
    }
}
