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
 * Class RequestDecoratorHandler
 */
final class RequestDecoratorHandler implements HandlerInterface
{
    /**
     * @var HandlerInterface
     */
    private HandlerInterface $handler;

    /**
     * EmptyPipelineHandler constructor.
     *
     * @param HandlerInterface $handler
     */
    public function __construct(HandlerInterface $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws \Throwable
     */
    public function handle(RequestInterface $request): ResponseInterface
    {
        return $this->handler->handle($request);
    }
}
