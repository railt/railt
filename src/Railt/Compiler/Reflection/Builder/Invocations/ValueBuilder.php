<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Builder\Invocations;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Compiler\Reflection\Builder\DocumentBuilder;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Contracts\Invocations\InputInvocation;

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
     * @var Document|DocumentBuilder
     */
    private $document;

    /**
     * ValueBuilder constructor.
     * @param Document $document
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    /**
     * @param TreeNode $ast
     * @param string $type
     * @param array $path
     * @return array|float|int|null|string
     */
    public function parse(TreeNode $ast, string $type, array $path = [])
    {
        switch ($ast->getId()) {
            case self::AST_ID_ARRAY:
                return $this->toArray($ast, $type, $path);

            case self::AST_ID_OBJECT:
                return $this->toObject($ast, $type, $path);
        }

        return $this->toScalar($ast);
    }

    /**
     * @param TreeNode $ast
     * @param string $type
     * @param array $path
     * @return array
     */
    private function toArray(TreeNode $ast, string $type, array $path): array
    {
        $result = [];

        /** @var TreeNode $child */
        foreach ($ast->getChildren() as $child) {
            $result[] = $this->parse($child->getChild(0), $type, $path);
        }

        return $result;
    }

    /**
     * @param TreeNode $ast
     * @param string $type
     * @param array $path
     * @return InputInvocation
     */
    private function toObject(TreeNode $ast, string $type, array $path): InputInvocation
    {
        return new InputInvocationBuilder($ast, $this->document, $type, $path);
    }

    /**
     * @param TreeNode $ast
     * @return float|int|string|null
     */
    private function toScalar(TreeNode $ast)
    {
        switch ($ast->getValueToken()) {
            case self::TOKEN_NUMBER:
                if (\strpos((string)$ast->getValueValue(), '.') !== false) {
                    return $this->toFloat($ast);
                }

                return $this->toInt($ast);

            case self::TOKEN_NULL:
                return;

            case self::TOKEN_BOOL_TRUE:
                return true;

            case self::TOKEN_BOOL_FALSE:
                return false;
        }

        return $this->toString($ast);
    }

    /**
     * @param TreeNode $ast
     * @return float
     */
    private function toFloat(TreeNode $ast): float
    {
        return (float)$ast->getValueValue();
    }

    /**
     * @param TreeNode $ast
     * @return int
     */
    private function toInt(TreeNode $ast): int
    {
        return (int)$ast->getValueValue();
    }

    /**
     * @param TreeNode $ast
     * @return string
     */
    private function toString(TreeNode $ast): string
    {
        $result = (string)$ast->getValueValue();

        // Transform utf char \uXXXX -> X
        $result = $this->renderUtfSequences($result);

        // Transform special chars
        $result = $this->renderSpecialCharacters($result);

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
    private function renderSpecialCharacters(string $body): string
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
    private function renderUtfSequences(string $body): string
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
