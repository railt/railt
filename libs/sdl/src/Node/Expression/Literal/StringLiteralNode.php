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
        '\\b' => 'b',
        '\\f' => 'f',
        '\\n' => 'n',
        '\\r' => 'r',
        '\\t' => 't',
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
    ) {}

    public static function parse(string $value): self
    {
        if ($value === '') {
            return new self('');
        }

        $value = self::parseAscii($value);
        $value = self::parseUnicode($value);

        return new self($value);
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
}
