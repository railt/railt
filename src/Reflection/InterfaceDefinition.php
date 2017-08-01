<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Reflection;

use Hoa\Compiler\Llk\TreeNode;
use Serafim\Railgun\Compiler\Dictionary;
use Serafim\Railgun\Reflection\Abstraction\InterfaceTypeInterface;
use Serafim\Railgun\Reflection\Common\HasDirectives;
use Serafim\Railgun\Reflection\Common\HasFields;

/**
 * Class InterfaceDefinition
 * @package Serafim\Railgun\Reflection
 */
class InterfaceDefinition extends Definition implements InterfaceTypeInterface
{
    use HasFields;
    use HasDirectives;

    /**
     * @param TreeNode $ast
     * @param Dictionary $dictionary
     */
    protected function compile(TreeNode $ast, Dictionary $dictionary): void
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }
}
