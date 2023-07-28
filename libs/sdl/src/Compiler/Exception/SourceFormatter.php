<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Exception;

use Railt\SDL\Exception\RuntimeExceptionInterface;
use Railt\SDL\Compiler\Exception\Formatter\PrettyReader;
use Railt\SDL\Compiler\Exception\Formatter\ReaderInterface;
use Phplrt\Contracts\Position\IntervalInterface;
use Phplrt\Contracts\Position\PositionInterface;
use Phplrt\Contracts\Source\ReadableInterface;
use Phplrt\Position\Interval;
use Phplrt\Position\Position;
use SebastianBergmann\Environment\Console;

final class SourceFormatter
{
    /**
     * @var non-empty-string
     */
    public const DEFAULT_EOL = \PHP_EOL;

    /**
     * @var int<0, max>
     */
    public const DEFAULT_CODE_SIZE = 2;

    /**
     * Source reader implementation.
     */
    private ReaderInterface $reader;

    /**
     * Count of lines before and after expected error line.
     *
     * @var int<0, max>
     */
    private int $size;

    /**
     * Enables or disables color support.
     *
     * @var bool
     */
    private bool $colors;

    /**
     * @var non-empty-string
     */
    private string $eol;

    /**
     * @param int<0, max> $size
     * @param non-empty-string $eol
     */
    public function __construct(
        ?ReaderInterface $reader = null,
        int $size = self::DEFAULT_CODE_SIZE,
        bool $colors = null,
        string $eol = self::DEFAULT_EOL
    ) {
        $this->size = $size;
        $this->reader = $reader ?? new PrettyReader();
        $this->colors = $colors ?? $this->hasColorSupport();
        $this->eol = $eol;
    }

    private function hasColorSupport(): bool
    {
        if (($_SERVER['TERM'] ?? '') === 'xterm') {
            return true;
        }

        if (\class_exists(Console::class)) {
            return (new Console())->hasColorSupport();
        }

        return true;
    }

    private function getInternal(ReadableInterface $source, PositionInterface $position): IntervalInterface
    {
        if ($this->size === 0) {
            return new Interval($position, $position);
        }

        $from = \max(1, $position->getLine() - $this->size);
        $to = \max(1, $position->getLine() + $this->size);

        return new Interval(
            Position::fromPosition($source, $from),
            Position::fromPosition($source, $to),
        );
    }

    public function format(RuntimeExceptionInterface $e): string
    {
        $source = $e->getSource();
        $position = $e->getPosition();

        $result = [];

        foreach ($this->reader->lines($source, $this->getInternal($source, $position)) as $line => $code) {
            $shouldHighlight = $this->size > 0
                && $position->getLine() === $line
            ;

            if ($shouldHighlight) {
                $result[] = \rtrim($this->formatHighlightedCodeLine($line, $code));

                continue;
            }

            $result[] = \rtrim($this->formatCodeLine($line, $code));
        }

        return \implode($this->eol, $result);
    }

    private function formatCodeLine(int $line, string $code): string
    {
        if ($this->colors) {
            return \sprintf("\e[38;5;240m %03d │\e[0m %s", $line, $code);
        }

        return \sprintf(' %3d │ %s', $line, $code);
    }

    private function formatHighlightedCodeLine(int $line, string $code): string
    {
        $code = \rtrim($code);

        if ($this->colors) {
            $codeStartsAt = $this->codeStartsAt($code);

            return \vsprintf("\e[38;5;160m %03d |%s\e[0m\e[48;5;160m %s \e[0m", [
                $line,
                \substr($code, 0, $codeStartsAt),
                \substr($code, $codeStartsAt),
            ]);
        }

        return \sprintf('➜ %3d | %s ', $line, $code);
    }

    private function codeStartsAt(string $code): int
    {
        return \strlen($code) - \strlen(\ltrim($code));
    }
}
