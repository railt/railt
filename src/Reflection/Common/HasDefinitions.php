<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Reflection\Common;

use Serafim\Railgun\Compiler\Dictionary;
use Serafim\Railgun\Reflection\Abstraction\DefinitionInterface;
use Serafim\Railgun\Reflection\Abstraction\DocumentTypeInterface;
use Serafim\Railgun\Reflection\Abstraction\NamedDefinitionInterface;

/**
 * Trait HasDefinitions
 * @package Serafim\Railgun\Reflection\Common
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
        /** @var DocumentTypeInterface $this */
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
        if (!property_exists($this, 'dictionary')) {
            throw new \LogicException(static::class . '::$dictionary property is not defined');
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
