<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Reflection\Common;

use Railt\Reflection\Contracts\DefinitionInterface;
use Railt\Reflection\Contracts\DocumentInterface;
use Railt\Reflection\Contracts\NamedDefinitionInterface;
use Railt\Reflection\Dictionary;

/**
 * Trait HasDefinitions
 * @package Railt\Reflection\Reflection\Common
 */
trait HasDefinitions
{
    /**
     * @return iterable
     * @throws \LogicException
     */
    public function getNamedDefinitions(): iterable
    {
        yield from $this->getDefinitions(NamedDefinitionInterface::class);
    }

    /**
     * @param string[]|DefinitionInterface[] ...$types
     * @return iterable|DefinitionInterface[]
     * @throws \LogicException
     */
    public function getDefinitions(string ...$types): iterable
    {
        /** @var DocumentInterface $this */
        $definitions = $this->getDefinitionsDictionary()->definitions($this);

        yield from count($types) !== 0
            ? $this->getDefinitionsFilteredBy($definitions, $types)
            : $definitions;
    }

    /**
     * @return Dictionary
     * @throws \LogicException
     */
    private function getDefinitionsDictionary(): Dictionary
    {
        if (! property_exists($this, 'dictionary')) {
            $error = sprintf('%s::$dictionary property is not defined', static::class);
            throw new \LogicException($error);
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
     * @throws \LogicException
     */
    public function hasDefinition(string $name): bool
    {
        return null !== $this->getDefinition($name);
    }

    /**
     * @param string $name
     * @return null|NamedDefinitionInterface
     * @throws \LogicException
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
