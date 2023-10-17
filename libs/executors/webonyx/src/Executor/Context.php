<?php

declare(strict_types=1);

namespace Railt\Executor\Webonyx\Executor;

use Psr\EventDispatcher\EventDispatcherInterface;
use Railt\Foundation\ConnectionInterface;
use Railt\Contracts\Http\RequestInterface;

final class Context
{
    public function __construct(
        public readonly ConnectionInterface $connection,
        public readonly RequestInterface $request,
        public readonly EventDispatcherInterface $dispatcher,
    ) {}
}
