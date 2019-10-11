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
use Railt\Http\Pipeline\MiddlewareInterface;
use Railt\Http\Pipeline\Handler\HandlerInterface;

/**
 * Class ExecutionTimeMiddleware
 */
class ExecutionTimeMiddleware implements MiddlewareInterface
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
     * @param HandlerInterface $next
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request, HandlerInterface $next): ResponseInterface
    {
        $timings = \microtime(true);

        $response = $next->handle($request);

        if ($this->config->get('debug', false)) {
            return $response->extend('time', $this->collect($timings));
        }


        return $response;
    }

    /**
     * @param float $timings
     * @return string
     */
    private function collect(float $timings): string
    {
        return $this->format(\microtime(true) - $timings);
    }

    /**
     * @param float $timings
     * @return string
     */
    private function format(float $timings): string
    {
        foreach (['s', 'ms', 'µs'] as $suffix) {
            if ($timings > 1) {
                return \number_format($timings, 4) . $suffix;
            }

            $timings *= 1000;
        }

        return \number_format($timings, 4) . 'ns';
    }
}

