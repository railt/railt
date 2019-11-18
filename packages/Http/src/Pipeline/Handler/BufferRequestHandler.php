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

/**
 * Class BufferRequestHandler
 */
final class BufferRequestHandler implements HandlerInterface
{
    /**
     * @var ResponseInterface
     */
    private ResponseInterface $response;

    /**
     * @var \Throwable|null
     */
    private ?\Throwable $exception = null;

    /**
     * BufferRequestHandler constructor.
     *
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @param ResponseInterface $response
     * @return BufferRequestHandler|$this
     */
    public function withResponse(ResponseInterface $response): self
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @param \Throwable $exception
     * @return BufferRequestHandler|$this
     */
    public function withException(\Throwable $exception): self
    {
        $this->exception = $exception;

        return $this;
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws \Throwable
     */
    public function handle(RequestInterface $request): ResponseInterface
    {
        try {
            if ($this->exception) {
                throw $this->exception;
            }

            return $this->response;
        } finally {
            $this->exception = null;
        }
    }
}
