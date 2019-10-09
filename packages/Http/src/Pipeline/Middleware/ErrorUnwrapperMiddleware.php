<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Pipeline\Middleware;

use Railt\Http\RequestInterface;
use Railt\Http\ResponseInterface;
use Railt\Config\RepositoryInterface;
use Railt\Http\Pipeline\RequestMiddlewareInterface;
use Railt\Http\Pipeline\RequestHandlerInterface;

/**
 * Class ErrorUnwrapperMiddleware
 */
class ErrorUnwrapperMiddleware implements RequestMiddlewareInterface
{
    /**
     * @var RepositoryInterface
     */
    private RepositoryInterface $config;

    /**
     * ErrorUnwrapperMiddleware constructor.
     *
     * @param RepositoryInterface $config
     */
    public function __construct(RepositoryInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @param RequestInterface $request
     * @param RequestHandlerInterface $next
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request, RequestHandlerInterface $next): ResponseInterface
    {
        $response = $next->handle($request);

        if ($this->config->get('debug', false)) {
            foreach ($response->getExceptions() as $exception) {
                $exception->publish();
            }
        }

        return $response;
    }
}

