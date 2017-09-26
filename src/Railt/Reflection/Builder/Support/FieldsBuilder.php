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
use Railt\Reflection\Base\Containers\BaseFieldsContainer;
use Railt\Reflection\Builder\FieldBuilder;
use Railt\Reflection\Contracts\Behavior\Nameable;
use Railt\Reflection\Contracts\Containers\HasFields;

/**
 * Trait FieldsBuilder
 */
trait FieldsBuilder
{
    /**
     * @param TreeNode $ast
     * @return bool
     */
    protected function compileFieldsBuilder(TreeNode $ast): bool
    {
        /** @var Nameable|BaseFieldsContainer $this */
        if ($this instanceof HasFields && $ast->getId() === '#Field') {
            $field = new FieldBuilder($ast, $this->getDocument(), $this);
            $this->fields[$field->getName()] = $field;

            return true;
        }

        return false;
    }
}
