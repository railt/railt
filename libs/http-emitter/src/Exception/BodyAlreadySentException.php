<?php

declare(strict_types=1);

namespace Railt\Http\Emitter\Exception;

class BodyAlreadySentException extends BodyEmittingException
{
    public const CODE_ALREADY_SENT = 0x01;

    public static function fromAlreadySentState(): self
    {
        return new static('Output has been emitted previously', self::CODE_ALREADY_SENT);
    }
}
