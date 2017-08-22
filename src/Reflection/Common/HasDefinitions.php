<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Common;

use Railt\Compiler\Dictionary;
use Railt\Exceptions\IndeterminateBehaviorException;
use Railt\Reflection\Abstraction\DefinitionInterface;
use Railt\Reflection\Abstraction\DocumentTypeInterface;
use Railt\Reflection\Abstraction\NamedDefinitionInterface;

/**
 * Trait HasDefinitions
 * @package Railt\Reflection\Common
 */
trait HasDefinitions
{
    /**
     * @return iterable
     */
    public function getNamedDefinitions(): iterable
    {
        yield from $this->getDefinitions(NamedDefinitionInterface::class);
    }

    /**
     * @param string[]|DefinitionInterface[] ...$types
     * @return iterable|DefinitionInterface[]
     */
    public function getDefinitions(string ...$types): iterable
    {
        /** @var DocumentTypeInterface $this */
        $definitions = $this->getDefinitionsDictionary()->definitions($this);

        yield from count($types) !== 0
            ? $this->getDefinitionsFilteredBy($definitions, $types)
            : $definitions;
    }

    /**
     * @return Dictionary
     */
    private function getDefinitionsDictionary(): Dictionary
    {
        if (!property_exists($this, 'dictionary')) {
            throw IndeterminateBehaviorException::new(
                '%s::$dictionary property is not defined',
                static::class
            );
        }

        return $this->dictionary;
    }

    /**
     * @param iterable|DefinitionInterface[] $definitions
     * @param array|string[] $types
     * @return \Traversable
     */
    private function getDefinitionsFilteredBy(iterable $definitions, array $types): \Traversable
    {
        foreach ($definitions as $definition) {
            foreach ($types as $type) {
                if ($definition instanceof $type) {
                    yield $definition;
                }
            }
        }
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasDefinition(string $name): bool
    {
        return null !== $this->getDefinition($name);
    }

    /**
     * @param string $name
     * @return null|NamedDefinitionInterface
     */
    public function getDefinition(string $name): ?NamedDefinitionInterface
    {
        foreach ($this->getDefinitions() as $definition) {
            if ($definition instanceof NamedDefinitionInterface && $definition->getName() === $name) {
                return $definition;
            }
        }

        return null;
    }
}
