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
use Serafim\Railgun\Reflection\Abstraction\DefinitionInterface;
use Serafim\Railgun\Reflection\Abstraction\ExtendTypeInterface;
use Serafim\Railgun\Reflection\Common\HasDirectives;
use Serafim\Railgun\Reflection\Common\HasFields;

/**
 * Class ExtendDefinition
 * @package Serafim\Railgun\Reflection
 */
class ExtendDefinition extends Definition implements ExtendTypeInterface
{
    use HasFields;
    use HasDirectives;

    protected function compile(TreeNode $ast, Dictionary $dictionary): void
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    public function getTarget(): DefinitionInterface
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }
}
