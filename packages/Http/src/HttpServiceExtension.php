<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Http\Pipeline\RequestPipeline;
use Railt\Foundation\Extension\Status;
use Railt\Container\ContainerInterface;
use Railt\Foundation\Extension\Extension;
use Railt\Http\Pipeline\PipelineInterface;
use Railt\Http\Pipeline\RequestPipelineInterface;
use Railt\Http\Pipeline\Handler\EmptyRequestHandler;
use Railt\Http\Pipeline\Middleware\RequestDumpMiddleware;
use Railt\Http\Pipeline\Middleware\ExecutionTimeMiddleware;
use Railt\Http\Pipeline\Middleware\ErrorUnwrapperMiddleware;
use Railt\Http\Pipeline\Middleware\ExecutionMemoryMiddleware;
use Railt\Http\Pipeline\Middleware\ExceptionHandlerMiddleware;

/**
 * Class HttpServiceExtension
 */
class HttpServiceExtension extends Extension
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->register(HttpKernelInterface::class,
            fn() => new HttpKernel(new EmptyRequestHandler(static::class))
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'Http';
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription(): string
    {
        return 'Provides GraphQL HTTP Kernel services';
    }

    /**
     * {@inheritDoc}
     */
    public function getStatus(): string
    {
        return Status::STABLE;
    }

    /**
     * {@inheritDoc}
     */
    public function getVersion(): string
    {
        return $this->app->getVersion();
    }
}
