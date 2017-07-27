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
 * Class Definition
 * @package Serafim\Railgun\Compiler\Reflection
 */
abstract class Definition extends Reflection
{
    /**
     * @return string
     */
    abstract public static function getAstNodeId(): string;
}

