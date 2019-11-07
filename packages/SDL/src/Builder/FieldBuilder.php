<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Builder;

use GraphQL\Contracts\TypeSystem\DefinitionInterface;
use GraphQL\Contracts\TypeSystem\FieldInterface;
use Railt\SDL\Ast\Definition\FieldDefinitionNode;
use Railt\SDL\TypeSystem\Field;

/**
 * @property FieldDefinitionNode $ast
 */
class FieldBuilder extends TypeBuilder
{
    /**
     * @return FieldInterface|DefinitionInterface
     */
    public function build(): FieldInterface
    {
        $field = new Field();
        $field->name = $this->ast->name->value;

        $field->type = $this->buildType($this->ast->type);
        $field->description = $this->description($this->ast->description);
        $field->arguments = \iterator_to_array($this->buildArguments($this->ast->arguments));

        return $field;
    }
}
