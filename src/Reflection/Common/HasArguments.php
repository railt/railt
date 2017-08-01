<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Reflection\Common;

use Hoa\Compiler\Llk\TreeNode;
use Serafim\Railgun\Compiler\Dictionary;
use Serafim\Railgun\Compiler\Exceptions\CompilerException;
use Serafim\Railgun\Reflection\Abstraction\ArgumentInterface;

/**
 * Trait HasArguments
 * @package Serafim\Railgun\Reflection\Common
 */
trait HasArguments
{
    /**
     * @param TreeNode $ast
     * @param Dictionary $dictionary
     */
    protected function compileHasArguments(TreeNode $ast, Dictionary $dictionary): void
    {
        $allowed = in_array($ast->getId(), $this->astHasArguments ?? ['#Argument'], true);

        if ($allowed) {
            throw new CompilerException('TODO: Add arguments compilation for ' . get_class($this));
        }
    }

    /**
     * @return iterable|ArgumentInterface[]
     */
    public function getArguments(): iterable
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasArgument(string $name): bool
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    /**
     * @param string $name
     * @return null|ArgumentInterface
     */
    public function getArgument(string $name): ?ArgumentInterface
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }
}
