<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Pipeline;

use Railt\Http\RequestInterface;
use Railt\Http\ResponseInterface;

/**
 * Class Next
 */
class Next implements RequestHandlerInterface
{
    /**
     * @var RequestHandlerInterface
     */
    private RequestHandlerInterface $handler;

    /**
     * @var RequestMiddlewareInterface
     */
    private RequestMiddlewareInterface $middleware;

    /**
     * Next constructor.
     *
     * @param RequestMiddlewareInterface $middleware
     * @param RequestHandlerInterface $handler
     */
    public function __construct(RequestMiddlewareInterface $middleware, RequestHandlerInterface $handler)
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
