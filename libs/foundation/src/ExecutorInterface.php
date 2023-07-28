<?php

declare(strict_types=1);

namespace Railt\Foundation;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Railt\Contracts\Http\ConnectionInterface;
use Railt\Contracts\Http\Middleware\RequestHandlerInterface;
use Railt\SDL\DictionaryInterface;

interface ExecutorInterface
{
    public function load(
        ConnectionInterface $connection,
        DictionaryInterface $types,
        EventDispatcherInterface $dispatcher,
    ): RequestHandlerInterface;
}
