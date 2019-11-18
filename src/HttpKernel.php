<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation;

use Railt\Container\ContainerInterface;
use Railt\Http\Pipeline\RequestPipeline;
use Railt\Contracts\Http\InputInterface;
use Railt\Contracts\Http\OutputInterface;
use Railt\Contracts\Http\RequestInterface;
use Railt\Contracts\Http\ResponseInterface;
use Railt\Contracts\Pipeline\PipelineInterface;
use Railt\Contracts\Pipeline\Http\HandlerInterface;
use Railt\Contracts\Pipeline\Http\HttpMiddlewareInterface;
use Railt\Http\Pipeline\Middleware\Debug\ExecutionMiddleware;
use Railt\Http\Pipeline\Middleware\ExceptionHandlerMiddleware;
use Railt\Http\Pipeline\Middleware\EmptyRequestGuardMiddleware;
use Railt\Http\Pipeline\Middleware\Debug\ErrorUnwrapperMiddleware;

/**
 * Class HttpKernel
 */
class HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every http request to
     * your application.
     *
     * @var array|HttpMiddlewareInterface[]
     */
    protected array $middleware = [
        ExceptionHandlerMiddleware::class,
        EmptyRequestGuardMiddleware::class,
        'debug',
    ];

    /**
     * The application's middleware groups.
     *
     * @var array|HttpMiddlewareInterface[][]
     */
    protected array $middlewareGroups = [
        'debug' => [
            ExecutionMiddleware::class,
            ErrorUnwrapperMiddleware::class,
        ],
    ];

    /**
     * @var PipelineInterface|RequestPipeline
     */
    private PipelineInterface $pipeline;

    /**
     * @var ContainerInterface
     */
    private ContainerInterface $app;

    /**
     * HttpKernel constructor.
     *
     * @param ContainerInterface $app
     */
    public function __construct(ContainerInterface $app)
    {
        $this->app = $app;

        $this->reboot();
    }

    /**
     * @param PipelineInterface $pipeline
     * @return void
     */
    private function boot(PipelineInterface $pipeline): void
    {
        foreach ($this->middleware as $name) {
            if (\is_array($this->middlewareGroups[$name] ?? null)) {
                $pipeline->through(...$this->middlewareGroups[$name]);
            } else {
                $pipeline->through($name);
            }
        }
    }

    /**
     * @return void
     */
    public function reboot(): void
    {
        $this->boot($this->pipeline = new RequestPipeline($this->app));
    }

    /**
     * @param ContainerInterface $app
     * @param RequestInterface $request
     * @param HandlerInterface $handler
     * @return ResponseInterface
     */
    public function handle(
        ContainerInterface $app,
        RequestInterface $request,
        HandlerInterface $handler
    ): ResponseInterface {
        return $this->pipeline
            ->using($app)
            ->send($request, $handler);
    }

    public function call(ContainerInterface $app, InputInterface $input, HandlerInterface $action): OutputInterface
    {
        throw new \LogicException(\sprintf('%s not implemented yet', __METHOD__));
    }
}
