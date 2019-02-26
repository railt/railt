<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\AST\Value;

use Railt\Parser\Ast\LeafInterface;

/**
 * Class StringValue
 * @internal This type is generated using a parser, please do not use it inside your application code.
 */
final class StringValue extends Value
{
    /**
     * @var string
     */
    private const UTF_SEQUENCE_PATTERN = '/(?<!\\\\)\\\\u([0-9a-f]{4})/ui';

    /**
     * @var string
     */
    private const CHAR_SEQUENCE_PATTERN = '/(?<!\\\\)\\\\(b|f|n|r|t)/u';

    /**
     * @var string[]
     */
    private const SPECIAL_CHARS_MAPPING = [
        'b' => "\u{0008}",
        'f' => "\u{000C}",
        'n' => "\u{000A}",
        'r' => "\u{000D}",
        't' => "\u{0009}",
    ];

    /**
     * @var string
     */
    public $value;

    /**
     * @param mixed $value
     * @return bool
     */
    protected function each($value): bool
    {
        if ($value instanceof LeafInterface) {
            $this->value = $this->parse($value->getValue(1));

            return true;
        }

        return parent::each($value);
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'string';
    }

    /**
     * @param string $value
     * @return string
     */
    private function parse(string $value): string
    {
        // Encode slashes to special "pattern" chars
        $value = $this->encodeSlashes($value);

        // Transform utf char \uXXXX -> X
        $value = $this->renderUtfSequences($value);

        // Transform special chars
        $value = $this->renderSpecialCharacters($value);

        // Decode special patterns to source chars (rollback)
        $value = $this->decodeSlashes($value);

        return $value;
    }

    /**
     * @param string $value
     * @return string
     */
    private function encodeSlashes(string $value): string
    {
        return \str_replace(['\\\\', '\\"'], ["\0", '"'], $value);
    }

    /**
     * Method for parsing and decode utf-8 character
     * sequences like "\uXXXX" type.
     *
     * @see http://facebook.github.io/graphql/October2016/#sec-String-Value
     * @param string $body
     * @return string
     */
    private function renderUtfSequences(string $body): string
    {
        $callee = function (array $matches): string {
            [$char, $code] = [$matches[0], $matches[1]];

            try {
                return $this->forwardRenderUtfSequences($code);
            } catch (\Error | \ErrorException $error) {
                return $this->fallbackRenderUtfSequences($char);
            }
        };

        return @\preg_replace_callback(self::UTF_SEQUENCE_PATTERN, $callee, $body) ?? $body;
    }

    /**
     * @param string $body
     * @return string
     */
    private function forwardRenderUtfSequences(string $body): string
    {
        return \mb_convert_encoding(\pack('H*', $body), 'UTF-8', 'UCS-2BE');
    }

    /**
     * @param string $body
     * @return string
     */
    private function fallbackRenderUtfSequences(string $body): string
    {
        try {
            if (\function_exists('\\json_decode')) {
                $result = @\json_decode('{"char": "' . $body . '"}')->char;

                if (\json_last_error() === \JSON_ERROR_NONE) {
                    $body = $result;
                }
            }
        } finally {
            return $body;
        }
    }

    /**
     * Method for parsing special control characters.
     *
     * @see http://facebook.github.io/graphql/October2016/#sec-String-Value
     * @param string $body
     * @return string
     */
    private function renderSpecialCharacters(string $body): string
    {
        $callee = function (array $matches): string {
            [$char, $code] = [$matches[0], $matches[1]];

            return self::SPECIAL_CHARS_MAPPING[$code] ?? $char;
        };

        return @\preg_replace_callback(self::CHAR_SEQUENCE_PATTERN, $callee, $body) ?? $body;
    }

    /**
     * @param string $value
     * @return string
     */
    private function decodeSlashes(string $value): string
    {
        return \str_replace("\0", '\\', $value);
    }
}
