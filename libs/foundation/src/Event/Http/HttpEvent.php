<?php

declare(strict_types=1);

namespace Railt\Foundation\Event\Http;

use Railt\Contracts\Http\ConnectionInterface;

abstract class HttpEvent
{
    public function __construct(
        public readonly ConnectionInterface $connection,
    ) {}
}
