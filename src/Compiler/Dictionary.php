<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Compiler;

use Serafim\Railgun\Compiler\Exceptions\TypeNotFoundException;
use Serafim\Railgun\Reflection\Abstraction\DefinitionInterface;
use Serafim\Railgun\Reflection\Abstraction\DocumentTypeInterface;
use Serafim\Railgun\Reflection\Abstraction\NamedDefinitionInterface;

/**
 * Class Dictionary
 * @package Serafim\Railgun\Compiler
 */
class Dictionary implements \Countable, \IteratorAggregate
{
    /**
     * @var array
     */
    private $definitions = [];

    /**
     * @var array
     */
    private $namedDefinitions = [];

    /**
     * @var Autoloader
     */
    private $loader;

    /**
     * @var array
     */
    private $context = [];

    /**
     * Dictionary constructor.
     * @param Autoloader $loader
     */
    public function __construct(Autoloader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * @param DefinitionInterface $definition
     * @return Dictionary
     */
    public function register(DefinitionInterface $definition): Dictionary
    {
        if ($definition instanceof NamedDefinitionInterface) {
            $this->registerNamedDefinition($definition);
        } else {
            $this->registerAnonymousDefinition($definition);
        }

        $this->registerCached($definition);

        return $this;
    }

    /**
     * @param DefinitionInterface $definition
     */
    private function registerCached(DefinitionInterface $definition): void
    {
        // Add hash map for fast type resolving from Document
        $documentId = $definition->getDocument()->getId();

        if (!array_key_exists($documentId, $this->context)) {
            $this->context[$documentId] = [];
        }

        $this->context[$documentId][] = $definition;
    }

    /**
     * @param NamedDefinitionInterface $definition
     */
    private function registerNamedDefinition(NamedDefinitionInterface $definition): void
    {
        $this->namedDefinitions[$definition->getName()] = $definition;
    }

    /**
     * @param DefinitionInterface $definition
     */
    private function registerAnonymousDefinition(DefinitionInterface $definition): void
    {
        $this->definitions[] = $definition;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->definitions) + count($this->namedDefinitions);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->namedDefinitions);
    }

    /**
     * @param string $name
     * @return NamedDefinitionInterface
     * @throws TypeNotFoundException
     */
    public function get(string $name): NamedDefinitionInterface
    {
        $parent = null;
        $error  = 'Type "%s" not found and could not be loaded';

        if ($this->has($name)) {
            return $this->namedDefinitions[$name];
        }

        try {
            if ($result = $this->loader->load($name)) {
                return $result;
            }
        } catch (\Exception $e) {
            [$error, $parent] = ['Error while loading type "%s"', $e];
        }

        throw new TypeNotFoundException(sprintf($error, $name), 0, $parent);
    }

    /**
     * @param DocumentTypeInterface $document
     * @return array
     */
    public function definitions(DocumentTypeInterface $document): array
    {
        return $this->context[$document->getId()] ?? [];
    }

    /**
     * @return \Generator|\Traversable
     */
    public function getIterator(): \Traversable
    {
        yield from array_values($this->definitions);
        yield from array_values($this->namedDefinitions);
    }
}
