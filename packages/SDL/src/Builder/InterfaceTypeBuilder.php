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
use GraphQL\Contracts\TypeSystem\Type\InterfaceTypeInterface;
use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;
use GraphQL\Contracts\TypeSystem\Type\TypeInterface;
use GraphQL\TypeSystem\Type\InterfaceType;
use Railt\SDL\Ast\Definition\InterfaceTypeDefinitionNode;
use Railt\SDL\Ast\Generic\InterfaceImplementsCollection;
use Railt\SDL\Ast\Type\NamedTypeNode;

/**
 * @property InterfaceTypeDefinitionNode $ast
 */
class InterfaceTypeBuilder extends TypeBuilder
{
    /**
     * @return DefinitionInterface|InterfaceTypeInterface
     * @throws \RuntimeException
     */
    public function build(): InterfaceTypeInterface
    {
        $interface = new InterfaceType([
            'name'        => $this->ast->name->value,
            'description' => $this->value($this->ast->description),
        ]);

        $this->register($interface);

        if ($this->ast->fields) {
            $interface = $interface->withFields($this->makeAll($this->ast->fields));
        }

        if ($this->ast->interfaces) {
            $interface = $interface->withInterfaces($this->buildImplementedInterfaces($this->ast->interfaces));
        }

        return $interface;
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
