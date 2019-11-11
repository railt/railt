<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Builder\Common;

use GraphQL\Contracts\TypeSystem\ArgumentInterface;
use GraphQL\Contracts\TypeSystem\DefinitionInterface;
use Railt\SDL\Ast\DefinitionNode;
use Railt\SDL\Ast\Generic\InputValueDefinitionCollection;

/**
 * Trait ArgumentsBuilderTrait
 */
trait ArgumentsBuilderTrait
{
    /**
     * @param InputValueDefinitionCollection|null $arguments
     * @return \Traversable|ArgumentInterface[]
     */
    protected function buildArguments(?InputValueDefinitionCollection $arguments): \Traversable
    {
        if ($arguments === null) {
            return new \EmptyIterator();
        }

        foreach ($arguments as $argument) {
            /** @var ArgumentInterface $definition */
            $definition = $this->buildDefinition($argument);

            yield $definition->getName() => $definition;
        }
    }

    /**
     * @param DefinitionNode $node
     * @return DefinitionInterface
     */
    abstract protected function buildDefinition(DefinitionNode $node): DefinitionInterface;
}
