<?php

declare(strict_types=1);

namespace Railt\SDL\Exception;

use Phplrt\Contracts\Position\PositionInterface;
use Phplrt\Contracts\Source\ReadableInterface;

/**
 * @psalm-consistent-constructor
 */
class RuntimeException extends \RuntimeException implements RuntimeExceptionInterface
{
    protected const CODE_LAST = 0x00;

    final public function __construct(
        string $message,
        private readonly ReadableInterface $source,
        private readonly PositionInterface $position,
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getSource(): ReadableInterface
    {
        return $this->source;
    }

    public function getPosition(): PositionInterface
    {
        return $this->position;
    }
}
