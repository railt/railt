<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Builder;

use GraphQL\TypeSystem\Field;
use GraphQL\Contracts\TypeSystem\DefinitionInterface;
use GraphQL\Contracts\TypeSystem\FieldInterface;
use Railt\SDL\Ast\Definition\FieldDefinitionNode;
use Railt\SDL\Builder\Common\ArgumentsBuilderTrait;

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
        return new Field([
            'name'        => $this->ast->name->value,
            'description' => $this->value($this->ast->description),
            'type'        => $this->buildType($this->ast->type),
            'arguments'   => $this->buildArguments($this->ast->arguments),
        ]);
    }
}
