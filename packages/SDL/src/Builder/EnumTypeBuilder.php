<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Builder;

use GraphQL\TypeSystem\Type\EnumType;
use GraphQL\Contracts\TypeSystem\EnumValueInterface;
use Railt\SDL\Ast\Definition\EnumTypeDefinitionNode;
use GraphQL\Contracts\TypeSystem\Type\EnumTypeInterface;
use Railt\SDL\Ast\Generic\EnumValueDefinitionCollection;

/**
 * @property EnumTypeDefinitionNode $ast
 */
class EnumTypeBuilder extends TypeBuilder
{
    /**
     * @return EnumTypeInterface
     * @throws \RuntimeException
     */
    public function build(): EnumTypeInterface
    {
        $enum = new EnumType([
            'name'        => $this->ast->name->value,
            'description' => $this->value($this->ast->description),
        ]);

        $this->registerType($enum);

        return $enum->withValues($this->buildEnumValues($this->ast->values));
    }

    /**
     * @param EnumValueDefinitionCollection|null $values
     * @return \Traversable
     */
    protected function buildEnumValues(?EnumValueDefinitionCollection $values): \Traversable
    {
        if ($values === null) {
            return new \EmptyIterator();
        }

        foreach ($values as $value) {
            /** @var EnumValueInterface $definition */
            $definition = $this->buildDefinition($value);

            yield $definition->getName() => $definition;
        }
    }
}
