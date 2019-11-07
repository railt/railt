<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Builder;

use Railt\SDL\TypeSystem\Type\ObjectType;
use GraphQL\Contracts\TypeSystem\DefinitionInterface;
use Railt\SDL\Ast\Definition\ObjectTypeDefinitionNode;
use GraphQL\Contracts\TypeSystem\Type\ObjectTypeInterface;

/**
 * @property-read ObjectTypeDefinitionNode $ast
 */
class ObjectTypeBuilder extends TypeBuilder
{
    /**
     * @return ObjectTypeInterface|DefinitionInterface
     */
    public function build(): ObjectTypeInterface
    {
        $object = new ObjectType();
        $object->name = $this->ast->name->value;

        $this->registerType($object);

        $object->description = $this->description($this->ast->description);
        $object->fields = \iterator_to_array($this->buildFields($this->ast->fields));
        $object->interfaces = \iterator_to_array($this->buildImplementedInterfaces($this->ast->interfaces));

        return $object;
    }
}
