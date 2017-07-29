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
 * Class SchemaDefinition
 * @package Serafim\Railgun\Compiler\Reflection
 */
class SchemaDefinition extends Definition
{
    /**
     * @return string
     */
    public static function getType(): string
    {
        return 'Schema';
    }

    /**
     * @return string
     */
    public static function getAstId(): string
    {
        return '#SchemaDefinition';
    }
}
