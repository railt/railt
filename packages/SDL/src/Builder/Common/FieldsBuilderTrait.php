<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Builder\Common;

use GraphQL\Contracts\TypeSystem\DefinitionInterface;
use GraphQL\Contracts\TypeSystem\FieldInterface;
use Railt\SDL\Ast\DefinitionNode;
use Railt\SDL\Ast\Generic\FieldDefinitionCollection;

/**
 * Trait FieldsBuilderTrait
 */
trait FieldsBuilderTrait
{
    /**
     * @param FieldDefinitionCollection|null $fields
     * @return \Traversable|FieldInterface[]
     */
    protected function buildFields(?FieldDefinitionCollection $fields): \Traversable
    {
        if ($fields === null) {
            return new \EmptyIterator();
        }

        foreach ($fields as $field) {
            /** @var FieldInterface $definition */
            $definition = $this->buildDefinition($field);

            yield $definition->getName() => $definition;
        }
    }

    /**
     * @param DefinitionNode $node
     * @return DefinitionInterface
     */
    abstract protected function buildDefinition(DefinitionNode $node): DefinitionInterface;
}
