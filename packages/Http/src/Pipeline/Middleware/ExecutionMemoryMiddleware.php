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
 * Class ExecutionMemoryMiddleware
 */
class ExecutionMemoryMiddleware implements MiddlewareInterface
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
        $response = $next->handle($request);

        if ($this->config->get('debug', false)) {
            return $response->extend('memory', $this->getMemory());
        }

        return $response;
    }

    /**
     * @return array
     */
    private function getMemory(): array
    {
        return [
            'current' => $this->format(\memory_get_usage()),
            'peak'    => $this->format(\memory_get_peak_usage()),
        ];
    }

    /**
     * @param float $timings
     * @return string
     */
    private function format(float $timings): string
    {
        foreach (['b', 'Kb', 'Mb'] as $suffix) {
            if ($timings < 1000) {
                return \number_format($timings, 4) . $suffix;
            }

            $timings /= 1000;
        }

        return \number_format($timings, 4) . 'Gb';
    }
}

