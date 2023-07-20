<?php

declare(strict_types=1);

namespace Railt\Http\Emitter;

use Railt\Contracts\Http\ResponseInterface;
use Railt\Http\Emitter\Exception\EmitterExceptionInterface;

interface EmitterInterface
{
    /**
     * Emits the HTTP response from GraphQL response, that including status
     * line, headers and message body, according to the environment.
     *
     * When implementing this method, MAY use `header()` and the output buffer.
     *
     * @throws EmitterExceptionInterface In case that emitter cannot emit a
     *         response, e.g., if headers already sent or output has been
     *         emitted previously.
     */
    public function emit(ResponseInterface $response): void;
}
