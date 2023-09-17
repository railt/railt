<?php

declare(strict_types=1);

namespace Railt\SDL\Node\Expression\Literal;

use voku\helper\UTF8;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL
 */
final class StringLiteralNode extends LiteralNode
{
    /**
     * @var array<non-empty-string, non-empty-string>
     */
    private const ESCAPE_CHARACTERS_MAP = [
        '\\"' => '"',
        '\\\\' => '\\',
        '\\/' => '/',
        '\\b' => "\x08",
        '\\f' => "\f",
        '\\n' => "\n",
        '\\r' => "\r",
        '\\t' => "\t",
    ];

    /**
     * @var non-empty-string
     */
    private const UNICODE_CHARS_PCRE = '/'
        . '\\\u([0-9a-fA-F]{4})'
        . '|\\\u\{([0-9a-fA-F]{4,})}'
        . '/um';

    public function __construct(
        public string $value,
        public ?string $representation = null,
    ) {}

    public static function parseInlineString(string $value): self
    {
        $value = \substr($value, 1, -1);

        return self::parse($value);
    }

    public static function parseMultilineString(string $value): self
    {
        $value = \substr($value, 3, -3);

        $indentation = self::getMinIndentation($value);

        if ($indentation !== 0) {
            $lines = [];

            foreach (\explode("\n", $value) as $i => $line) {
                // Skip first line (GraphQL.js compatibility)
                if ($i === 0) {
                    $lines[] = $line;
                    continue;
                }

                $lines[] = \substr($line, $indentation);
            }

            $value = \implode("\n", $lines);
        }

        $value = \rtrim(\ltrim($value, "\n"));

        return self::parse($value);
    }

    /**
     * @return int<0, max>
     */
    private static function getMinIndentation(string $text): int
    {
        $indentation = \PHP_INT_MAX;

        foreach (\explode("\n", $text) as $i => $line) {
            // Skip first line (GraphQL.js compatibility)
            if ($i === 0) {
                continue;
            }

            $current = \strlen($line) - \strlen(\ltrim($line));
            $indentation = \min($current, $indentation);

            if ($indentation === 0) {
                break;
            }
        }

        /** @var int<0, max> */
        return $indentation;
    }

    public static function parse(string $value): self
    {
        if ($value === '') {
            return new self('', '');
        }

        $parsed = self::parseAscii($value);
        $parsed = self::parseUnicode($parsed);

        return new self($parsed, $value);
    }

    /**
     * @param string $value
     * @return ($value is non-empty-string ? non-empty-string : string)
     */
    private static function parseUnicode(string $value): string
    {
        return \preg_replace_callback(
            self::UNICODE_CHARS_PCRE,
            static function (array $matches): string {
                return (string)UTF8::chr(\hexdec($matches[2] ?? $matches[1] ?: '0'));
            },
            $value,
        ) ?: $value;
    }

    /**
     * @param string $value
     * @return ($value is non-empty-string ? non-empty-string : string)
     */
    private static function parseAscii(string $value): string
    {
        return \str_replace(
            \array_keys(self::ESCAPE_CHARACTERS_MAP),
            \array_values(self::ESCAPE_CHARACTERS_MAP),
            $value,
        );
    }

    public function __toString(): string
    {
        $expression = $this->representation ?? $this->value;

        return \sprintf('"%s"', \addslashes($expression));
    }
}
