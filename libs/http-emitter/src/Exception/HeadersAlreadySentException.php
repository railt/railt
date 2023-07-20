<?php

declare(strict_types=1);

namespace Railt\Http\Emitter\Exception;

class HeadersAlreadySentException extends EmitterException
{
    public const CODE_ALREADY_SENT = 0x01;

    public static function fromAlreadySentState(): self
    {
        return new static('Headers already sent', self::CODE_ALREADY_SENT);
    }
}
