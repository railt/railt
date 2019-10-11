<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Container\ContainerInterface;
use Railt\Http\Pipeline\RequestPipeline;
use Railt\Http\Pipeline\PipelineInterface;
use Railt\Http\Pipeline\MiddlewareInterface;
use Railt\Http\Pipeline\Handler\HandlerInterface;

/**
 * Class HttpKernel
 */
class HttpKernel implements HttpKernelInterface
{
    /**
     * @var array|MiddlewareInterface[]
     */
    protected array $middleware = [
        \Railt\Http\Pipeline\Middleware\ExceptionHandlerMiddleware::class,
        \Railt\Http\Pipeline\Middleware\ExecutionMemoryMiddleware::class,
        \Railt\Http\Pipeline\Middleware\ErrorUnwrapperMiddleware::class,
        \Railt\Http\Pipeline\Middleware\ExecutionTimeMiddleware::class,
        \Railt\Http\Pipeline\Middleware\RequestDumpMiddleware::class,
    ];

    /**
     * @var PipelineInterface|RequestPipeline
     */
    private PipelineInterface $request;

    /**
     * HttpKernel constructor.
     */
    public function __construct()
    {
        $this->request = (new RequestPipeline())
            ->through(...$this->middleware);
    }

    /**
     * @param ContainerInterface $app
     * @param RequestInterface $request
     * @param HandlerInterface $handler
     * @return ResponseInterface
     */
    public function handle(ContainerInterface $app, RequestInterface $request, HandlerInterface $handler): ResponseInterface
    {
        return $this->request->send($app, $request, $handler);
    }

    public function call(ContainerInterface $app, InputInterface $input, HandlerInterface $action): OutputInterface
    {
        throw new \LogicException(\sprintf('%s not implemented yet', __METHOD__));
    }
}
