<?php

declare(strict_types=1);

namespace Railt\Foundation;

use Railt\Contracts\Http\Middleware\RequestHandlerInterface;
use Railt\TypeSystem\DictionaryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

interface ExecutorInterface
{
    public function load(
        ConnectionInterface $connection,
        DictionaryInterface $types,
        EventDispatcherInterface $dispatcher,
    ): RequestHandlerInterface;
}
