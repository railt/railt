<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Builder;

use GraphQL\Contracts\TypeSystem\EnumValueInterface;
use GraphQL\Contracts\TypeSystem\Type\EnumTypeInterface;
use Railt\SDL\Ast\Definition\EnumTypeDefinitionNode;
use Railt\SDL\Ast\Generic\EnumValueDefinitionCollection;
use Railt\SDL\TypeSystem\Type\EnumType;

/**
 * @property EnumTypeDefinitionNode $ast
 */
class EnumTypeBuilder extends TypeBuilder
{
    /**
     * @return EnumTypeInterface
     */
    public function build(): EnumTypeInterface
    {
        $enum = new EnumType();
        $enum->name = $this->ast->name->value;

        $this->registerType($enum);

        $enum->description = $this->description($this->ast->description);
        $enum->values = \iterator_to_array($this->buildEnumValues($this->ast->values));

        return $enum;
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
