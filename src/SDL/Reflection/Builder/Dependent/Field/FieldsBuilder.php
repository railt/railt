<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection\Builder\Dependent\Field;

use Railt\Compiler\Parser\Ast\NodeInterface;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Contracts\Dependent\Field\HasFields;
use Railt\SDL\Exceptions\TypeConflictException;
use Railt\SDL\Reflection\Builder\Dependent\FieldBuilder;
use Railt\SDL\Reflection\Builder\Process\Compiler;

/**
 * Trait FieldsBuilder
 *
 * @mixin Compiler
 */
trait FieldsBuilder
{
    /**
     * @param NodeInterface $ast
     * @return bool
     * @throws TypeConflictException
     */
    protected function compileFieldsBuilder(NodeInterface $ast): bool
    {
        /** @var TypeDefinition|HasFields $this */
        if ($this instanceof HasFields && $ast->is('#Field')) {
            $field = new FieldBuilder($ast, $this->getDocument(), $this);

            $this->fields = $this->unique($this->fields, $field);

            return true;
        }

        return false;
    }
}
