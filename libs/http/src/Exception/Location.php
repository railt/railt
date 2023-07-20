<?php

declare(strict_types=1);

namespace Railt\Http\Exception;

use Railt\Contracts\Http\Error\LocationInterface;

final class Location implements LocationInterface
{
    /**
     * @param int<1, max> $line
     * @param int<1, max> $column
     */
    public function __construct(
        private int $line = 1,
        private int $column = 1,
    ) {
    }

    public function getLine(): int
    {
        return $this->line;
    }

    public function withLine(int $line = 1): self
    {
        $self = clone $this;
        $self->setLine($line);

        return $self;
    }

    /**
     * Mutable equivalent of {@see LocationInterface::withLine()} method.
     *
     * @link LocationInterface::withLine() method description.
     *
     * @param int<1, max> $line
     */
    public function setLine(int $line = 1): void
    {
        $this->line = $line;
    }

    public function getColumn(): int
    {
        return $this->column;
    }

    public function withColumn(int $column = 1): self
    {
        $self = clone $this;
        $self->setColumn($column);

        return $self;
    }

    /**
     * Mutable equivalent of {@see LocationInterface::withColumn()} method.
     *
     * @link LocationInterface::withColumn() method description.
     *
     * @param int<1, max> $column
     */
    public function setColumn(int $column = 1): void
    {
        $this->column = $column;
    }
}
