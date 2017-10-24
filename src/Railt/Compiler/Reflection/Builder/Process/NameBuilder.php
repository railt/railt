<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Builder\Process;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Compiler\Exceptions\TypeConflictException;

/**
 * Trait NameBuilder
 */
trait NameBuilder
{
    /**
     * @param TreeNode $ast
     * @return void
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    protected function precompileNameableType(TreeNode $ast): void
    {
        /**
         * @var TreeNode $child
         */
        foreach ($ast->getChildren() as $child) {
            switch ($child->getId()) {
                case '#Name':
                    $this->name = $this->parseName($child);
                    break;

                case '#Description':
                    $this->description = $this->parseDescription($child);
                    break;
            }
        }
    }

    /**
     * @param TreeNode $ast
     * @return string
     * @throws TypeConflictException
     */
    private function parseName(TreeNode $ast): string
    {
        return $ast->getChild(0)->getValueValue();
    }

    /**
     * @param TreeNode $ast
     * @return string
     */
    private function parseDescription(TreeNode $ast): string
    {
        $description = \trim($ast->getChild(0)->getValueValue());

        return $description
            ? \preg_replace('/^\h*#?\h+(.*?)\h*$/imsu', '$1', $description)
            : $description;
    }
}
