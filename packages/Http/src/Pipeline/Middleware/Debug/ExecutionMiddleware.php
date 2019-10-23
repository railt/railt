<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Pipeline\Middleware\Debug;

use Railt\Contracts\Http\RequestInterface;
use Railt\Contracts\Http\ResponseInterface;
use Railt\Contracts\Config\RepositoryInterface;
use Railt\Contracts\Pipeline\Http\HandlerInterface;

/**
 * Class ExecutionMemoryMiddleware
 */
class ExecutionMiddleware extends DebuggingMiddleware
{
    /**
     * @var float|mixed
     */
    private float $bootedAt;

    /**
     * ExecutionMiddleware constructor.
     *
     * @param RepositoryInterface $config
     */
    public function __construct(RepositoryInterface $config)
    {
        parent::__construct($config);

        $this->bootedAt = \microtime(true);
    }

    /**
     * @param RequestInterface $request
     * @param HandlerInterface $next
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request, HandlerInterface $next): ResponseInterface
    {
        $startsAt = \microtime(true);

        $response = $next->handle($request);

        if ($this->isDebug()) {
            return $response->withExtension('execution', [
                'time'           => $this->getTime($startsAt),
                'uptime'         => $this->getTime($this->bootedAt),
                'current_memory' => $this->getCurrentMemory(),
                'peak_memory'    => $this->getPeakMemory(),
            ]);
        }

        return $response;
    }

    /**
     * @param float $startsAt
     * @return string
     */
    private function getTime(float $startsAt): string
    {
        return $this->formatTime(\microtime(true) - $startsAt);
    }

    /**
     * @param float $timings
     * @return string
     */
    private function formatTime(float $timings): string
    {
        foreach (['s', 'ms', 'µs'] as $suffix) {
            if ($timings > 1) {
                return \number_format($timings, 4) . $suffix;
            }

            $timings *= 1000;
        }

        return \number_format($timings, 4) . 'ns';
    }

    /**
     * @return string
     */
    private function getCurrentMemory(): string
    {
        return $this->formatMemory(\memory_get_usage());
    }

    /**
     * @param float $timings
     * @return string
     */
    private function formatMemory(float $timings): string
    {
        foreach (['b', 'Kb', 'Mb'] as $suffix) {
            if ($timings < 1000) {
                return \number_format($timings, 4) . $suffix;
            }

            $timings /= 1000;
        }

        return \number_format($timings, 4) . 'Gb';
    }

    /**
     * @return string
     */
    private function getPeakMemory(): string
    {
        return $this->formatMemory(\memory_get_peak_usage());
    }
}
