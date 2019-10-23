<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Pipeline;

use Railt\Contracts\Http\RequestInterface;
use Railt\Contracts\Http\ResponseInterface;
use Railt\Contracts\Pipeline\Http\HandlerInterface;
use Railt\Contracts\Pipeline\Http\RequestPipelineInterface;

/**
 * Class Pipeline
 */
class RequestPipeline extends Pipeline implements RequestPipelineInterface
{
    /**
     * @param RequestInterface $request
     * @param HandlerInterface $handler
     * @return ResponseInterface
     */
    public function send(RequestInterface $request, HandlerInterface $handler): ResponseInterface
    {
        \assert($request instanceof RequestInterface);

        return $this->handler($this->app, $handler)->handle($request);
    }
}
