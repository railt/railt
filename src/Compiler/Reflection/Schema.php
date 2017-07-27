<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Compiler\Reflection;

/**
 * Class Schema
 * @package Serafim\Railgun\Compiler\Reflection
 */
class Schema extends Definition
{
    /**
     * Defined in grammar
     */
    private const AST_NODE_NAME = '#SchemaDefinition';

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return null;
    }

    public function getQuery(): Reflection
    {

    }

    public function getMutation(): ?Reflection
    {

    }

    /**
     * @return string
     */
    public static function getAstNodeId(): string
    {
        return self::AST_NODE_NAME;
    }
}
