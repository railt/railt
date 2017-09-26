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
use Railt\Reflection\Base\Behavior\BaseName;
use Railt\Reflection\Exceptions\TypeConflictException;

/**
 * Trait NameBuilder
 */
trait NameBuilder
{
    /**
     * @param TreeNode $ast
     * @return void
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    protected function precompileNameableType(TreeNode $ast): void
    {
        /**
         * @var BaseName $this
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
        $name = $ast->getChild(0)->getValueValue();

        if ($name) {
            return $name;
        }

        throw new TypeConflictException('Type name can not be empty');
    }

    /**
     * @param TreeNode $ast
     * @return string
     */
    private function parseDescription(TreeNode $ast): string
    {
        $description = $ast->getChild(0)->getValueValue();

        if (\trim($description)) {
            $description = \preg_replace('/^#?\h+(.*?)$/imsu', '$1', $description);
            return \trim($description);
        }

        return $description;
    }
}
