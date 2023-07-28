<?php

declare(strict_types=1);

namespace Railt\Foundation;

use Railt\Contracts\Http\Middleware\RequestHandlerInterface;
use Railt\Contracts\Http\RequestInterface;
use Railt\Contracts\Http\ResponseInterface;
use Railt\Http\Middleware\MutablePipelineInterface;
use Railt\Http\Middleware\Pipeline;
use Railt\TypeSystem\DictionaryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class Connection implements ConnectionInterface
{
    public readonly MutablePipelineInterface $pipeline;
    private readonly RequestHandlerInterface $handler;

    public function __construct(
        ExecutorInterface $executor,
        DictionaryInterface $types,
        EventDispatcherInterface $dispatcher,
    ) {
        $this->pipeline = new Pipeline();

        $this->handler = $executor->load($this, $types, $dispatcher);
    }

    public function handle(RequestInterface $request): ResponseInterface
    {
        return $this->pipeline->process($request, $this->handler);
    }
}
