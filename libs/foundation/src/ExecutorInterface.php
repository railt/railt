<?php

declare(strict_types=1);

namespace Railt\Foundation;

use Railt\Contracts\Http\Middleware\RequestHandlerInterface;
use Railt\SDL\DictionaryInterface;

interface ExecutorInterface
{
    public function load(DictionaryInterface $types, ConnectionInterface $connection): RequestHandlerInterface;
}
