<?php

declare(strict_types=1);

namespace Railt\Foundation\Event\Http;

use Railt\Foundation\ConnectionInterface;
use Railt\Contracts\Http\RequestInterface;
use Railt\Contracts\Http\ResponseInterface;

final class ResponseProceed extends HttpEvent
{
    public function __construct(
        ConnectionInterface $connection,
        RequestInterface $request,
        public readonly ResponseInterface $response,
    ) {
        parent::__construct($connection, $request);
    }
}
