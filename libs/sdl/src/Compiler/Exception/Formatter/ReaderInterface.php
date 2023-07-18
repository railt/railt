<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Exception\Formatter;

use Phplrt\Contracts\Position\IntervalInterface;
use Phplrt\Contracts\Position\PositionInterface;
use Phplrt\Contracts\Source\ReadableInterface;

interface ReaderInterface
{
    /**
     * Returns expected line from the given source.
     *
     * @return string
     */
    public function line(ReadableInterface $source, PositionInterface $position): string;

    /**
     * Returns expected lines from the given source.
     *
     * @return iterable<int<1, max>, string>
     */
    public function lines(ReadableInterface $source, IntervalInterface $interval): iterable;
}
