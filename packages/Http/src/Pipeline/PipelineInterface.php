<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Pipeline;

use Railt\Http\ConnectionInterface;
use Railt\Http\RequestInterface;
use Railt\Http\ResponseInterface;

/**
 * Interface PipelineInterface
 */
interface PipelineInterface
{
    /**
     * @param RequestMiddlewareInterface|string $middleware
     * @return $this
     */
    public function through($middleware): self;

    /**
     * @param ConnectionInterface $conn
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function send(ConnectionInterface $conn, RequestInterface $request): ResponseInterface;

    /**
     * @param ConnectionInterface $conn
     * @param RequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function sendTo(ConnectionInterface $conn, RequestInterface $request, RequestHandlerInterface $handler): ResponseInterface;
}
