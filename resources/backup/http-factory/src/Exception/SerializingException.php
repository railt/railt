<?php

declare(strict_types=1);

namespace Railt\Http\Factory\Exception;

use Railt\Contracts\Http\Factory\Exception\SerializingExceptionInterface;

class SerializingException extends \RuntimeException implements
    SerializingExceptionInterface
{
    public const CODE_INVALID_FORMAT = 0x01;

    final public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function fromJsonException(\JsonException $e): self
    {
        $message = \vsprintf('An error occurred while serializing data to JSON: %s', [
            $e->getMessage(),
        ]);

        return new static($message, self::CODE_INVALID_FORMAT, $e);
    }
}
