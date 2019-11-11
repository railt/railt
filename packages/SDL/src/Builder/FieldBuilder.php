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
use Railt\SDL\Builder\Common\ArgumentsBuilderTrait;
use Railt\TypeSystem\Field;

/**
 * @property FieldDefinitionNode $ast
 */
class FieldBuilder extends TypeBuilder
{
    use ArgumentsBuilderTrait;

    /**
     * @return FieldInterface|DefinitionInterface
     */
    public function build(): FieldInterface
    {
        $field = new Field([
            'name'        => $this->ast->name->value,
            'description' => $this->value($this->ast->description),
            'type'        => $this->buildType($this->ast->type),
            'arguments'   => \iterator_to_array($this->buildArguments($this->ast->arguments)),
        ]);


        return $field;
    }
}
