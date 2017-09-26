<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder\Support;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Compiler\CompilerInterface;

/**
 * Trait TypeIndicationBuilder
 */
trait TypeIndicationBuilder
{
    /**
     * @param TreeNode $ast
     * @return bool
     */
    protected function compileTypeIndicationBuilder(TreeNode $ast): bool
    {
        switch ($ast->getId()) {
            case '#Type':
                return $this->buildType($ast);

            case '#List':
                return $this->buildList($ast);
        }

        return false;
    }

    /**
     * @param TreeNode $ast
     * @return bool
     */
    private function buildType(TreeNode $ast): bool
    {
        /** @var TreeNode $child */
        foreach ($ast->getChildren() as $child) {
            if ($child->getValueToken() === 'T_NON_NULL') {
                $this->isNonNull = true;
            } else {
                /** @var CompilerInterface $compiler */
                $compiler = $this->getCompiler();
                $this->type = $compiler->get($child->getValueValue());
            }
        }

        return true;
    }

    /**
     * @param TreeNode $ast
     * @return bool
     */
    private function buildList(TreeNode $ast): bool
    {
        $this->isList = true;

        /** @var TreeNode $child */
        foreach ($ast->getChildren() as $child) {
            if ($child->getId() === '#Type') {
                $this->buildType($child);
                continue;
            }

            if ($child->getValueToken() === 'T_NON_NULL') {
                $this->isNonNullList = true;
            }
        }

        return true;
    }
}
