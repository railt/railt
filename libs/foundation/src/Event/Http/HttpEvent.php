<?php

declare(strict_types=1);

namespace Railt\Foundation\Event\Http;

use Psr\EventDispatcher\StoppableEventInterface;
use Railt\Foundation\ConnectionInterface;
use Railt\Contracts\Http\RequestInterface;
use Railt\Foundation\Event\PropagationStoppableEvent;

abstract class HttpEvent implements StoppableEventInterface
{
    use PropagationStoppableEvent;

    public function __construct(
        public readonly ConnectionInterface $connection,
        public readonly RequestInterface $request,
    ) {}
}
