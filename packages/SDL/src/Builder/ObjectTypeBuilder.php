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
use GraphQL\Contracts\TypeSystem\Type\ObjectTypeInterface;
use GraphQL\TypeSystem\Type\ObjectType;
use Railt\SDL\Ast\Definition\ObjectTypeDefinitionNode;
use Railt\SDL\Builder\Common\FieldsBuilderTrait;

/**
 * @property ObjectTypeDefinitionNode $ast
 */
class ObjectTypeBuilder extends TypeBuilder
{
    use FieldsBuilderTrait;

    /**
     * @return ObjectTypeInterface|DefinitionInterface
     * @throws \RuntimeException
     */
    public function build(): ObjectTypeInterface
    {
        $object = new ObjectType([
            'name'        => $this->ast->name->value,
            'description' => $this->value($this->ast->description),
        ]);

        $this->registerType($object);

        return $object
            ->withFields($this->buildFields($this->ast->fields))
            ->withInterfaces($this->buildImplementedInterfaces($this->ast->interfaces))
        ;
    }
}
