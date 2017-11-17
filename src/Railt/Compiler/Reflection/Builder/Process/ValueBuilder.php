<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Builder\Process;

use Hoa\Compiler\Llk\TreeNode;

/**
 * Class ValueBuilder
 */
class ValueBuilder
{
    private const AST_ID_ARRAY  = '#List';
    private const AST_ID_OBJECT = '#Object';

    private const TOKEN_NULL       = 'T_NULL';
    private const TOKEN_NUMBER     = 'T_NUMBER_VALUE';
    private const TOKEN_BOOL_TRUE  = 'T_BOOL_TRUE';
    private const TOKEN_BOOL_FALSE = 'T_BOOL_FALSE';

    /**
     * @param TreeNode $ast
     * @return mixed
     */
    public static function parse(TreeNode $ast)
    {
        switch ($ast->getId()) {
            case self::AST_ID_ARRAY:
                return self::toArray($ast);

            case self::AST_ID_OBJECT:
                return self::toObject($ast);
        }

        return self::toScalar($ast);
    }

    /**
     * @param TreeNode $ast
     * @return array
     */
    private static function toArray(TreeNode $ast): array
    {
        $result = [];

        /** @var TreeNode $child */
        foreach ($ast->getChildren() as $child) {
            $result[] = self::parse($child->getChild(0));
        }

        return $result;
    }

    /**
     * @param TreeNode $ast
     * @return array
     */
    private static function toObject(TreeNode $ast): array
    {
        $result = [];

        /** @var TreeNode $child */
        foreach ($ast->getChildren() as $child) {
            $key          = (string)$child->getChild(0)->getChild(0)->getValueValue();
            $result[$key] = self::parse($child->getChild(1)->getChild(0));
        }

        return $result;
    }

    /**
     * @param TreeNode $ast
     * @return float|int|string
     */
    private static function toScalar(TreeNode $ast)
    {
        switch ($ast->getValueToken()) {
            case self::TOKEN_NUMBER:
                if (\strpos((string)$ast->getValueValue(), '.') !== false) {
                    return self::toFloat($ast);
                }

                return self::toInt($ast);

            case self::TOKEN_NULL:
                return;

            case self::TOKEN_BOOL_TRUE:
                return true;

            case self::TOKEN_BOOL_FALSE:
                return false;
        }

        return self::toString($ast);
    }

    /**
     * @param TreeNode $ast
     * @return float
     */
    private static function toFloat(TreeNode $ast): float
    {
        return (float)$ast->getValueValue();
    }

    /**
     * @param TreeNode $ast
     * @return int
     */
    private static function toInt(TreeNode $ast): int
    {
        return (int)$ast->getValueValue();
    }

    /**
     * @param TreeNode $ast
     * @return string
     */
    private static function toString(TreeNode $ast): string
    {
        $result = (string)$ast->getValueValue();

        // Transform utf char \uXXXX -> X
        $result = self::renderUtfSequences($result);

        // Transform special chars
        $result = self::renderSpecialCharacters($result);

        // Unescape slashes "Some\\Any" => "Some\Any"
        $result = \stripcslashes($result);

        return $result;
    }

    /**
     * Method for parsing special control characters.
     *
     * @see http://facebook.github.io/graphql/October2016/#sec-String-Value
     *
     * @param string $body
     * @return string
     */
    private static function renderSpecialCharacters(string $body): string
    {
        // TODO Probably may be escaped by backslash like "\\n".
        $source = ['\b', '\f', '\n', '\r', '\t'];
        $out    = ["\u{0008}", "\u{000C}", "\u{000A}", "\u{000D}", "\u{0009}"];

        return \str_replace($source, $out, $body);
    }

    /**
     * Method for parsing and decode utf-8 character
     * sequences like "\uXXXX" type.
     *
     * @see http://facebook.github.io/graphql/October2016/#sec-String-Value
     * @param string $body
     * @return string
     */
    private static function renderUtfSequences(string $body): string
    {
        // TODO Probably may be escaped by backslash like "\\u0000"
        $pattern = '/\\\\u([0-9a-fA-F]{4})/';

        $callee = function (array $matches): string {
            [$char, $code] = [$matches[0], $matches[1]];

            try {
                $rendered = \pack('H*', $code);

                return \mb_convert_encoding($rendered, 'UTF-8', 'UCS-2BE');
            } catch (\Error | \ErrorException $error) {
                // Fallback?
                return $char;
            }
        };

        return @\preg_replace_callback($pattern, $callee, $body) ?? $body;
    }
}
