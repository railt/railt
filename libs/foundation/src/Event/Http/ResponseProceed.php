<?php

declare(strict_types=1);

namespace Railt\Foundation\Event\Http;

use Railt\Contracts\Http\RequestInterface;
use Railt\Contracts\Http\ResponseInterface;
use Railt\Contracts\Http\ConnectionInterface;

final class ResponseProceed extends HttpEvent
{
    public function __construct(
        ConnectionInterface $connection,
        public readonly RequestInterface $request,
        public readonly ResponseInterface $response,
    ) {
        parent::__construct($connection);
    }
}
