<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder\Process;

use Hoa\Compiler\Llk\TreeNode;

/**
 * Class ValueBuilder
 */
class ValueBuilder
{
    private const AST_ID_ARRAY     = '#List';
    private const AST_ID_OBJECT    = '#Object';

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
    private static function toObject(TreeNode $ast): array
    {
        $result = [];

        /** @var TreeNode $child */
        foreach ($ast->getChildren() as $child) {
            $key = (string)$child->getChild(0)->getChild(0)->getValueValue();
            $result[$key] = self::parse($child->getChild(1)->getChild(0));
        }

        return $result;
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
                return null;

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
        return (string)$ast->getValueValue();
    }
}
