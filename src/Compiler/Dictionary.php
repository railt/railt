<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Compiler;

use Railgun\Exceptions\SemanticException;
use Railgun\Exceptions\TypeNotFoundException;
use Railgun\Reflection\Abstraction\DefinitionInterface;
use Railgun\Reflection\Abstraction\DocumentTypeInterface;
use Railgun\Reflection\Abstraction\NamedDefinitionInterface;

/**
 * Class Dictionary
 * @package Railgun\Compiler
 */
class Dictionary implements \Countable, \IteratorAggregate
{
    /**
     * @var array|DefinitionInterface[]
     */
    private $definitions = [];

    /**
     * @var array|NamedDefinitionInterface[]
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
     * @param bool $force
     * @return Dictionary
     * @throws \Railgun\Exceptions\SemanticException
     */
    public function register(DefinitionInterface $definition, bool $force = false): Dictionary
    {
        if ($definition instanceof NamedDefinitionInterface) {
            $this->registerNamedDefinition($definition, $force);
        } else {
            $this->registerAnonymousDefinition($definition);
        }

        $this->registerCached($definition);

        return $this;
    }

    /**
     * @param NamedDefinitionInterface $definition
     * @param bool $force
     * @throws \Railgun\Exceptions\SemanticException
     */
    private function registerNamedDefinition(NamedDefinitionInterface $definition, bool $force = false): void
    {
        if (!$force && array_key_exists($definition->getName(), $this->namedDefinitions)) {
            $this->throwDictionaryOverridingException($definition);
        }

        $this->namedDefinitions[$definition->getName()] = $definition;
    }

    /**
     * @param NamedDefinitionInterface $definition
     * @throws SemanticException
     */
    private function throwDictionaryOverridingException(NamedDefinitionInterface $definition): void
    {
        $target = $this->namedDefinitions[$definition->getName()];

        throw SemanticException::new(
            'Can not register type named "%s" as %s. Type "%s" already registered as %s',
            $definition->getName(),
            $definition->getTypeName(),
            $target->getName(),
            $target->getTypeName()
        );
    }

    /**
     * @param DefinitionInterface $definition
     */
    private function registerAnonymousDefinition(DefinitionInterface $definition): void
    {
        $this->definitions[] = $definition;
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
     * @return int
     */
    public function count(): int
    {
        return count($this->definitions) + count($this->namedDefinitions);
    }

    /**
     * @param string $name
     * @return NamedDefinitionInterface
     * @throws TypeNotFoundException
     * @throws \Railgun\Exceptions\NotReadableException
     * @throws \Railgun\Exceptions\UnexpectedTokenException
     * @throws \Railgun\Exceptions\UnrecognizedTokenException
     */
    public function find(string $name): NamedDefinitionInterface
    {
        try {
            return $this->get($name);
        } catch (TypeNotFoundException $error) {
            return $this->load($name);
        }
    }

    /**
     * @param string $name
     * @return NamedDefinitionInterface
     * @throws TypeNotFoundException
     * @throws \Railgun\Exceptions\NotReadableException
     * @throws \Railgun\Exceptions\UnexpectedTokenException
     * @throws \Railgun\Exceptions\UnrecognizedTokenException
     */
    private function load(string $name): NamedDefinitionInterface
    {
        if ($result = $this->loader->load($name)) {
            return $result;
        }

        throw TypeNotFoundException::fromLoader($name);
    }

    /**
     * @param string $name
     * @return NamedDefinitionInterface
     * @throws \Railgun\Exceptions\TypeNotFoundException
     */
    public function get(string $name): NamedDefinitionInterface
    {
        if ($this->has($name)) {
            return $this->namedDefinitions[$name];
        }

        throw TypeNotFoundException::basic(sprintf('Type "%s" not found', $name));
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
     * @param DocumentTypeInterface $document
     * @param string $name
     * @return null|NamedDefinitionInterface
     */
    public function definition(DocumentTypeInterface $document, string $name): ?NamedDefinitionInterface
    {
        return array_first($this->definitions($document), function (DefinitionInterface $definition) use ($name) {
            return $definition instanceof NamedDefinitionInterface && $definition->getName() === $name;
        });
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
     * @return \Traversable
     */
    public function all(): \Traversable
    {
        yield from $this->getIterator();
    }

    /**
     * @return \Generator|\Traversable
     */
    public function getIterator(): \Traversable
    {
        yield from $this->named();
        yield from $this->anonymous();
    }

    /**
     * @return \Traversable
     */
    public function named(): \Traversable
    {
        foreach ($this->namedDefinitions as $definition) {
            yield $definition->getDocument() => $definition;
        }
    }

    /**
     * @return \Traversable
     */
    public function anonymous(): \Traversable
    {
        foreach ($this->definitions as $definition) {
            yield $definition->getDocument() => $definition;
        }
    }
}
