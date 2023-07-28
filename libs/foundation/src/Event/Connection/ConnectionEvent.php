<?php

declare(strict_types=1);

namespace Railt\Foundation\Event\Connection;

use Railt\Contracts\Http\ConnectionInterface;

abstract class ConnectionEvent
{
    public function __construct(
        public readonly ConnectionInterface $connection,
    ) {}
}
