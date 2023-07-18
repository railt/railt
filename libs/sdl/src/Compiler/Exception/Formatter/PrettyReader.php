<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Exception\Formatter;

use Phplrt\Contracts\Position\IntervalInterface;
use Phplrt\Contracts\Position\PositionInterface;
use Phplrt\Contracts\Source\ReadableInterface;

final class PrettyReader implements ReaderInterface
{
    /**
     * List of characters that are whitespace.
     */
    public const DEFAULT_WHITESPACE_CHARS = " \t\n\r\0\x0B";

    /**
     * The set of characters in a string that are treated as nesting depth
     * characters.
     *
     * In most cases, these are spaces and tabs.
     */
    private string $depthChars;

    private ReaderInterface $reader;

    public function __construct(
        ?ReaderInterface $reader = null,
        string $depthChars = self::DEFAULT_WHITESPACE_CHARS
    ) {
        $this->reader = $reader ?? new Reader();
        $this->depthChars = $depthChars;
    }

    /**
     * Returns the nesting depth value for the given line.
     *
     * @return int<0, max>
     */
    private function getNestingLevel(string $text): int
    {
        /** @var int<0, max> */
        return \strlen($text) - \strlen(\ltrim($text, $this->depthChars));
    }

    /**
     * @param iterable<string> $lines
     * @return int<0, max>
     */
    private function getMinimalNestingLevel(iterable $lines): int
    {
        $level = \PHP_INT_MAX;

        foreach ($lines as $text) {
            // Compute minimal nesting level only if the line of code
            // contains non-empty text.
            if (\trim($text) !== '') {
                $level = \min($level, $this->getNestingLevel($text));
            }
        }

        if ($level === \PHP_INT_MAX) {
            return 0;
        }

        return $level;
    }

    public function line(ReadableInterface $source, PositionInterface $position): string
    {
        $result = $this->reader->line($source, $position);

        return \rtrim(
            \ltrim($result, $this->depthChars),
            "\r\n" . $this->depthChars,
        );
    }

    public function lines(ReadableInterface $source, IntervalInterface $interval): iterable
    {
        $result = $this->reader->lines($source, $interval);

        if ($result instanceof \Traversable) {
            $result = \iterator_to_array($result, true);
        }

        if ($result === []) {
            return [];
        }

        $level = $this->getMinimalNestingLevel($result);

        foreach ($result as $line => $text) {
            $result[$line] = \substr($text, $level);
        }

        return $result;
    }
}
