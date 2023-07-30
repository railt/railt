<?php

declare(strict_types=1);

namespace Railt\SDL\Generator\Value;

use Railt\SDL\Generator\Config;
use Railt\SDL\Generator\Generator;
use Railt\SDL\Generator\Internal\Printer;

final class StringValueGenerator extends Generator
{
    public function __construct(
        private readonly string $description,
        Config $config = new Config(),
    ) {
        parent::__construct($config);
    }

    public static function isMultilineString(mixed $value): bool
    {
        return \is_string($value) && \str_contains($value, "\n");
    }

    /**
     * @psalm-suppress MixedAssignment
     */
    public static function isOneOfMultilineString(iterable $values): bool
    {
        foreach ($values as $value) {
            if (self::isMultilineString($value)) {
                return true;
            }
        }

        return false;
    }

    private function escape(string $text): string
    {
        return \addcslashes($text, '"');
    }

    private function toInlineString(): string
    {
        return \sprintf('"%s"', $this->escape($this->description));
    }

    private function toMultilineString(): string
    {
        $lines = \explode("\n", $this->description);
        $lines = \array_map($this->escape(...), $lines);

        return $this->printer->join([
            '"""',
            ...$lines,
            '"""',
        ]);
    }

    public function __toString(): string
    {
        if (self::isMultilineString($this->description)) {
            return $this->toMultilineString();
        }

        return $this->toInlineString();
    }
}
