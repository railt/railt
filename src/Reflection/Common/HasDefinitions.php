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
use Serafim\Railgun\Compiler\Exceptions\TypeNotFoundException;
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
     * @var Dictionary
     */
    protected $dictionary;

    /**
     * @param string[]|DefinitionInterface[] ...$types
     * @return iterable|DefinitionInterface[]
     */
    public function getDefinitions(string ...$types): iterable
    {
        /** @var DocumentTypeInterface $this */
        $definitions = $this->dictionary->definitions($this);

        yield from count($types) !== 0
            ? $this->getDefinitionsFilteredBy($definitions, $types)
            : $definitions;
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
     * @return iterable
     */
    public function getNamedDefinitions(): iterable
    {
        yield from $this->getDefinitions(NamedDefinitionInterface::class);
    }

    /**
     * @param string $name
     * @return bool
     * @throws TypeNotFoundException
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
