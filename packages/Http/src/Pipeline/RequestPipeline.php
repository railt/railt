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
use Railt\Container\ContainerInterface;
use Railt\Http\Pipeline\Handler\HandlerInterface;

/**
 * Class Pipeline
 */
class RequestPipeline extends Pipeline
{
    /**
     * @param ContainerInterface $app
     * @param RequestInterface $request
     * @param HandlerInterface $handler
     * @return ResponseInterface
     */
    public function send(ContainerInterface $app, $request, HandlerInterface $handler): ResponseInterface
    {
        \assert($request instanceof RequestInterface);

        return $this->handler($app, $handler)->handle($request);
    }
}
