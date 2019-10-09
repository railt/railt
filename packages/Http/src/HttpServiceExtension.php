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
use Railt\Foundation\Extension\Extension;
use Railt\Foundation\Extension\Status;
use Railt\Http\Pipeline\Middleware\ErrorUnwrapperMiddleware;
use Railt\Http\Pipeline\Middleware\ExceptionHandlerMiddleware;
use Railt\Http\Pipeline\Middleware\ExecutionMemoryMiddleware;
use Railt\Http\Pipeline\Middleware\ExecutionTimeMiddleware;
use Railt\Http\Pipeline\Middleware\RequestDumpMiddleware;
use Railt\Http\Pipeline\Pipeline;
use Railt\Http\Pipeline\PipelineInterface;

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
        $handler = static function (ContainerInterface $app) {
            return (new Pipeline($app))
                ->through(ExceptionHandlerMiddleware::class)
                ->through(ErrorUnwrapperMiddleware::class)
                ->through(RequestDumpMiddleware::class)
                ->through(ExecutionTimeMiddleware::class)
                ->through(ExecutionMemoryMiddleware::class)
            ;
        };

        $this->app->register(PipelineInterface::class, $handler);
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
