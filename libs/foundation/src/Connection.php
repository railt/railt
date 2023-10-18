<?php

declare(strict_types=1);

namespace Railt\Foundation;

use Railt\Contracts\Http\Middleware\RequestHandlerInterface;
use Railt\Contracts\Http\RequestInterface;
use Railt\Contracts\Http\ResponseInterface;
use Railt\Foundation\Extension\Context;
use Railt\Foundation\Extension\RepositoryInterface;
use Railt\Http\Middleware\PipelineInterface;
use Railt\TypeSystem\DictionaryInterface;
use Railt\EventDispatcher\EventDispatcherInterface;

final class Connection implements ConnectionInterface
{
    private readonly RequestHandlerInterface $handler;

    public function __construct(
        private readonly Context $context,
        private readonly RepositoryInterface $extensions,
        ExecutorInterface $executor,
        DictionaryInterface $types,
        EventDispatcherInterface $dispatcher,
        private readonly PipelineInterface $pipeline,
    ) {
        $this->handler = $executor->load($this, $types, $dispatcher);
    }

    public function handle(RequestInterface $request): ResponseInterface
    {
        return $this->pipeline->process($request, $this->handler);
    }

    public function __destruct()
    {
        $this->extensions->unload($this->context);
    }
}
