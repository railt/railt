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
use Serafim\Railgun\Reflection\Abstraction\DirectiveTypeInterface;
use Serafim\Railgun\Reflection\Common\HasArguments;

/**
 * Class DirectiveDefinition
 * @package Serafim\Railgun\Reflection
 */
class DirectiveDefinition extends Definition implements DirectiveTypeInterface
{
    use HasArguments;

    protected function compile(TreeNode $ast, Dictionary $dictionary): void
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    public function getTargets(): iterable
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    public function hasTarget(string $name): bool
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    public function getTarget(string $name): ?string
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }
}
