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
use Serafim\Railgun\Reflection\Abstraction\InputTypeInterface;
use Serafim\Railgun\Reflection\Common\HasArguments;
use Serafim\Railgun\Reflection\Common\HasDirectives;

/**
 * Class InputDefinition
 * @package Serafim\Railgun\Reflection
 */
class InputDefinition extends Definition implements InputTypeInterface
{
    use HasArguments;
    use HasDirectives;

    protected function compile(TreeNode $ast, Dictionary $dictionary): ?TreeNode
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }
}
