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
use Railt\Http\Exception\GraphQLClientException;
use Railt\Http\Pipeline\Handler\HandlerInterface;

/**
 * Class EmptyRequestGuardMiddleware
 */
class EmptyRequestGuardMiddleware implements MiddlewareInterface
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
            return (new Response())->withException($this->getException());
        }

        return $next->handle($request);
    }

    /**
     * @return \Throwable
     */
    protected function getException(): \Throwable
    {
        $exception = new GraphQLClientException(self::ERROR_EMPTY_QUERY);
        $exception->withLocation(0,0);

        return $exception;
    }
}
