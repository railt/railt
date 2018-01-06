<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\Reflection\Builder\Dependent\Field;

use Railt\Compiler\TreeNode;
use Railt\GraphQL\Exceptions\TypeConflictException;
use Railt\GraphQL\Reflection\Builder\Dependent\FieldBuilder;
use Railt\GraphQL\Reflection\Builder\Process\Compiler;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Contracts\Dependent\Field\HasFields;

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
        /** @var TypeDefinition|HasFields $this */
        if ($this instanceof HasFields && $ast->getId() === '#Field') {
            $field = new FieldBuilder($ast, $this->getDocument(), $this);

            $this->fields = $this->unique($this->fields, $field);

            return true;
        }

        return false;
    }
}
