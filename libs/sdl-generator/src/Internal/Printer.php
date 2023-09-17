<?php

declare(strict_types=1);

namespace Railt\SDL\Generator\Internal;

use Railt\SDL\Generator\Config;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL\Generator
 */
final class Printer
{
    public function __construct(
        private readonly Config $config,
    ) {}

    /**
     * @param int<0, max> $level
     */
    public function prefix(int $level): string
    {
        return \str_repeat($this->config->indentation, $level);
    }

    /**
     * @param int<0, max> $level
     * @param list<int|float|string> $args
     */
    public function prefixed(int $level, string $message, array $args = []): string
    {
        $prefix = $this->prefix($level);

        $lines = [];

        foreach (\explode("\n", $message) as $item) {
            $lines[] = $prefix . $item;
        }

        $result = \implode("\n", $lines);

        if ($args !== []) {
            $result = \vsprintf($result, $args);
        }

        return $result;
    }

    /**
     * @psalm-suppress InvalidOperand : psalm bug
     *
     * @param iterable<string|\Stringable> $messages
     */
    public function join(iterable $messages): string
    {
        return \implode($this->config->delimiter, [
            ...$messages,
        ]);
    }
}
