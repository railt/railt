<?php

declare(strict_types=1);

namespace Railt\Foundation\Event\Http;

use Railt\Contracts\Http\RequestInterface;
use Railt\Contracts\Http\ConnectionInterface;

final class RequestReceived extends HttpEvent
{
    public function __construct(
        ConnectionInterface $connection,
        public readonly RequestInterface $request,
    ) {
        parent::__construct($connection);
    }
}
