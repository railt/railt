<?php

declare(strict_types=1);

namespace Railt\Http\Emitter\Exception;

use Railt\Contracts\Http\Factory\Exception\SerializingExceptionInterface;

class BodyEmittingException extends EmitterException
{
    public const CODE_SERIALIZATION_ERROR = 0x01;

    public static function fromSerializationException(SerializingExceptionInterface $exception): self
    {
        return new static(
            'An error occurred while serializing response body',
            self::CODE_SERIALIZATION_ERROR,
            $exception,
        );
    }
}
