<?php

declare(strict_types=1);

namespace Railt\SDL\Exception;

use Phplrt\Contracts\Position\PositionInterface;
use Phplrt\Contracts\Source\ReadableInterface;

class ParsingException extends RuntimeException
{
    final public const CODE_UNEXPECTED_TOKEN = 0x01 + parent::CODE_LAST;

    final public const CODE_UNRECOGNIZED_TOKEN = 0x02 + parent::CODE_LAST;

    final public const CODE_GENERIC_ERROR = 0x03 + parent::CODE_LAST;

    protected const CODE_LAST = self::CODE_GENERIC_ERROR;

    public static function fromUnexpectedToken(string $message, ReadableInterface $src, PositionInterface $pos): self
    {
        return new static($message, $src, $pos, self::CODE_UNEXPECTED_TOKEN);
    }

    public static function fromUnrecognizedToken(string $message, ReadableInterface $src, PositionInterface $pos): self
    {
        return new static($message, $src, $pos, self::CODE_UNRECOGNIZED_TOKEN);
    }

    public static function fromGenericError(string $message, ReadableInterface $src, PositionInterface $pos): self
    {
        return new static($message, $src, $pos, self::CODE_GENERIC_ERROR);
    }
}
