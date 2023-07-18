<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Exception\Formatter;

use Phplrt\Contracts\Position\IntervalInterface;
use Phplrt\Contracts\Position\PositionInterface;
use Phplrt\Contracts\Source\ReadableInterface;

class Reader implements ReaderInterface
{
    /**
     * Shifts the stream position to the specified line.
     *
     * @param resource $stream
     */
    private function seekToLine($stream, PositionInterface $position): void
    {
        $actual = 1;
        $expected = $position->getLine();

        while ($actual < $expected && !\feof($stream)) {
            \fgets($stream);
            $actual++;
        }
    }

    /**
     * @return resource
     */
    private function getStreamFromLine(ReadableInterface $source, PositionInterface $position): mixed
    {
        $stream = $source->getStream();

        $this->seekToLine($stream, $position);

        return $stream;
    }

    public function line(ReadableInterface $source, PositionInterface $position): string
    {
        $stream = $this->getStreamFromLine($source, $position);

        return (string)\fgets($stream);
    }

    /**
     * @return int<1, max>
     */
    private function getExpectedLine(IntervalInterface $interval): int
    {
        $to = $interval->getTo();

        return $to->getLine();
    }

    public function lines(ReadableInterface $source, IntervalInterface $interval): iterable
    {
        $from = $interval->getFrom();

        $actual = $from->getLine();
        $expected = $this->getExpectedLine($interval);

        $stream = $this->getStreamFromLine($source, $from);

        while ($expected >= $actual && !\feof($stream)) {
            yield $actual++ => \rtrim((string)\fgets($stream), "\r\n");
        }
    }
}
