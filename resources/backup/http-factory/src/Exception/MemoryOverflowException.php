<?php

declare(strict_types=1);

namespace Railt\Http\Factory\Exception;

use Railt\Contracts\Http\Factory\Exception\ParsingExceptionInterface;

class MemoryOverflowException extends \OverflowException implements ParsingExceptionInterface
{
    public const CODE_BODY_SIZE_OVERFLOW = 0x01;

    final public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @param int<1, max> $excepted
     */
    public static function fromBodySizeOverflow(int $excepted): self
    {
        $message = \sprintf('Body size limit of %d bytes exceeded', $excepted);

        return new static($message, self::CODE_BODY_SIZE_OVERFLOW);
    }
}
