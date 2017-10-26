<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Builder\Dependent\Field;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Compiler\Reflection\Builder\Dependent\FieldBuilder;
use Railt\Compiler\Reflection\Builder\Process\Compiler;
use Railt\Compiler\Reflection\Contracts\Behavior\Nameable;
use Railt\Compiler\Reflection\Contracts\Dependent\Field\HasFields;
use Railt\Compiler\Exceptions\TypeConflictException;

/**
 * Trait FieldsBuilder
 *
 * @mixin Compiler
 */
trait FieldsBuilder
{
    /**
     * @param TreeNode $ast
     * @return bool
     * @throws TypeConflictException
     */
    protected function compileFieldsBuilder(TreeNode $ast): bool
    {
        /** @var Nameable|HasFields $this */
        if ($this instanceof HasFields && $ast->getId() === '#Field') {
            $field = new FieldBuilder($ast, $this->getDocument(), $this);

            $this->fields = $this->getValidator()->uniqueDefinitions($this->fields, $field);

            return true;
        }

        return false;
    }
}
