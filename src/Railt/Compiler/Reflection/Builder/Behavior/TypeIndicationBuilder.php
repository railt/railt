<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Builder\Behavior;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Compiler\Exceptions\TypeNotFoundException;
use Railt\Compiler\Reflection\Builder\Process\Compilable;
use Railt\Compiler\Reflection\Contracts\Behavior\Nameable;
use Railt\Compiler\Reflection\Contracts\Dependent\DependentDefinition;

/**
 * Trait TypeIndicationBuilder
 *
 * @mixin Compilable
 */
trait TypeIndicationBuilder
{
    /**
     * @param TreeNode $ast
     * @return bool
     * @throws TypeNotFoundException
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
     * @throws \Railt\Compiler\Exceptions\TypeNotFoundException
     */
    private function buildType(TreeNode $ast): bool
    {
        /** @var TreeNode $child */
        foreach ($ast->getChildren() as $child) {
            if ($child->getValueToken() === 'T_NON_NULL') {
                if ($this->isList) {
                    $this->isListOfNonNulls = true;
                } else {
                    $this->isNonNull = true;
                }
            } else {
                $name = $child->getValueValue();
                $this->type = $this->getCompiler()->get($name);
            }
        }

        return true;
    }

    /**
     * @param TreeNode $ast
     * @return bool
     * @throws TypeNotFoundException
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
                $this->isNonNull = true;
            }
        }

        return true;
    }
}
