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
use Serafim\Railgun\Compiler\Dictionary;
use Serafim\Railgun\Compiler\Reflection\Support\HasFields;

/**
 * Class InterfaceDefinition
 * @package Serafim\Railgun\Compiler\Reflection
 */
class InterfaceDefinition extends Definition
{
    use HasFields;

    /**
     * @return string
     */
    public static function getType(): string
    {
        return 'Interface';
    }

    /**
     * @return string
     */
    public static function getAstId(): string
    {
        return '#InterfaceDefinition';
    }

    /**
     * @internal
     * @param TreeNode $node
     * @param Dictionary $dictionary
     * @return void
     */
    public function compile(TreeNode $node, Dictionary $dictionary): void
    {
        $this->compileFields($node, $dictionary);
    }
}
