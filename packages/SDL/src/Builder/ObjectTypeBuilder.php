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
use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;
use GraphQL\Contracts\TypeSystem\Type\ObjectTypeInterface;
use GraphQL\Contracts\TypeSystem\Type\TypeInterface;
use GraphQL\TypeSystem\Type\ObjectType;
use Railt\SDL\Ast\Definition\ObjectTypeDefinitionNode;
use Railt\SDL\Ast\Generic\InterfaceImplementsCollection;
use Railt\SDL\Ast\Type\NamedTypeNode;

/**
 * @property ObjectTypeDefinitionNode $ast
 */
class ObjectTypeBuilder extends TypeBuilder
{
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

        $this->register($object);

        if ($this->ast->fields) {
            $object = $object->withFields($this->makeAll($this->ast->fields));
        }

        if ($this->ast->interfaces) {
            $object = $object->withInterfaces($this->buildImplementedInterfaces($this->ast->interfaces));
        }


        return $object;
    }

    /**
     * @param InterfaceImplementsCollection|NamedTypeNode[]|null $interfaces
     * @return \Traversable|TypeInterface[]
     */
    protected function buildImplementedInterfaces(?InterfaceImplementsCollection $interfaces): \Traversable
    {
        foreach ($interfaces as $interface) {
            /** @var NamedTypeInterface $type */
            yield $this->fetch($interface->name->value);
        }
    }
}
