<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Compiler\Reflection;
use Hoa\Compiler\Llk\TreeNode;
use Serafim\Railgun\Compiler\Autoloader;

/**
 * Class ScalarDefinition
 * @package Serafim\Railgun\Compiler\Reflection
 */
class ScalarDefinition extends Definition
{
    /**
     * @return string
     */
    public static function getType(): string
    {
        return 'Scalar';
    }

    /**
     * @return string
     */
    public static function getAstId(): string
    {
        return '#ScalarDefinition';
    }

    /**
     * @internal
     * @param TreeNode $node
     * @param Autoloader $loader
     * @return void
     */
    public function compile(TreeNode $node, Autoloader $loader): void
    {
        // TODO: Implement compile() method.
    }
}
