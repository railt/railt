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
use GraphQL\Contracts\TypeSystem\Type\TypeInterface;
use GraphQL\Contracts\TypeSystem\Type\UnionTypeInterface;
use GraphQL\TypeSystem\Type\UnionType;
use Railt\SDL\Ast\Definition\UnionTypeDefinitionNode;
use Railt\SDL\Ast\Generic\TypeDefinitionCollection;
use Railt\SDL\Ast\Type\NamedTypeNode;

/**
 * @property UnionTypeDefinitionNode $ast
 */
class UnionTypeBuilder extends TypeBuilder
{
    /**
     * @return UnionTypeInterface|DefinitionInterface
     * @throws \RuntimeException
     */
    public function build(): UnionTypeInterface
    {
        $union = new UnionType([
            'name'        => $this->ast->name->value,
            'description' => $this->value($this->ast->description),
        ]);

        $this->register($union);

        if ($this->ast->types) {
            $union = $union->withTypes($this->buildTypes($this->ast->types));
        }

        return $union;
    }

    /**
     * @param TypeDefinitionCollection|NamedTypeNode[]|null $types
     * @return \Traversable|TypeInterface[]
     */
    protected function buildTypes(?TypeDefinitionCollection $types): \Traversable
    {
        foreach ($types as $type) {
            yield $this->fetch($type->name->value);
        }
    }
}
