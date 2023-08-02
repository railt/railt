<?php

declare(strict_types=1);

namespace Railt\Contracts\Http\Error;

/**
 * If an error can be associated to a particular point in the requested GraphQL
 * document, it should contain an entry with the key locations with a list of
 * locations, where each location is a map with the keys line and column, both
 * positive numbers starting from 1 which describe the beginning of an
 * associated syntax element.
 */
interface LocationInterface
{
    /**
     * Returns line position info.
     *
     * @return int<1, max>
     */
    public function getLine(): int;

    /**
     * Returns new instance of {@see LocationInterface} with the passed
     * line argument.
     *
     * @psalm-immutable This method MUST retain the state of the current
     *                  instance, and return an instance that contains the
     *                  specified line.
     *
     * @param int<1, max> $line This value (must be greater than 0).
     */
    public function withLine(int $line = 1): self;

    /**
     * Returns column position info.
     *
     * @return int<1, max>
     */
    public function getColumn(): int;

    /**
     * Returns new instance of {@see LocationInterface} with the passed
     * column argument.
     *
     * @psalm-immutable This method MUST retain the state of the current
     *                  instance, and return an instance that contains the
     *                  specified column.
     *
     * @param int<1, max> $column This value (must be greater than 0).
     */
    public function withColumn(int $column = 1): self;

    /**
     * @return array{line: int<1, max>, column: int<1, max>}
     */
    public function toArray(): array;
}
