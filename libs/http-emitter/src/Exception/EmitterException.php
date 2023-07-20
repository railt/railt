<?php

declare(strict_types=1);

namespace Railt\Http\Emitter\Exception;

class EmitterException extends \LogicException implements EmitterExceptionInterface
{
    final public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
