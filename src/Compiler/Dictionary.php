<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Compiler;

use Serafim\Railgun\Compiler\Exceptions\SemanticException;
use Serafim\Railgun\Compiler\Exceptions\TypeException;
use Serafim\Railgun\Compiler\Exceptions\TypeNotFoundException;
use Serafim\Railgun\Compiler\Reflection\Definition;

/**
 * Class Dictionary
 * @package Serafim\Railgun\Compiler
 */
class Dictionary
{
    /**
     * @var array|Definition[]
     */
    private $namedDefinitions = [];

    /**
     * @var array|Definition[]
     */
    private $definitions = [];

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
     * @param Definition $definition
     * @return Dictionary
     * @throws SemanticException
     */
    public function register(Definition $definition): Dictionary
    {
        $name = $definition->getName();

        if ($name !== null && $this->has($name)) {
            $error = $this->getRedefiningError($definition, $this->get($name));
            throw new TypeException($error, $definition->getContext()->getFileName());
        }


        $this->storeDefinition($definition);
        $this->storeContext($definition);

        return $this;
    }

    /**
     * @param Definition $definition
     */
    private function storeContext(Definition $definition): void
    {
        // Cache context
        $id = $definition->getContext()->getId();

        if (!array_key_exists($id, $this->context)) {
            $this->context[$id] = [];
        }

        $this->context[$id][] = $definition;
    }

    /**
     * @param Definition $definition
     * @throws \Serafim\Railgun\Compiler\Exceptions\SemanticException
     */
    private function storeDefinition(Definition $definition): void
    {
        $name = $definition->getName();

        // Register definition
        if ($name !== null) {
            $this->namedDefinitions[$name] = $definition;
        } else {
            $this->definitions[] = $definition;
        }
    }

    /**
     * @param Definition $target
     * @param Definition $original
     * @return string
     */
    private function getRedefiningError(Definition $target, Definition $original): string
    {
        $info = function(Definition $definition) {
            return [
                $definition->getName(),
                $definition::getType(),
                $definition->getContext()->getFileName()
            ];
        };

        $error   = 'Can not register type named "%s" as %s.';
        $because = 'Type "%s" already registered as %s';

        return sprintf($error, ...$info($target)) . ' ' .
            sprintf($because, ...$info($original));
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
     * @return Definition
     * @throws TypeNotFoundException
     */
    public function get(string $name): Definition
    {
        if ($this->has($name)) {
            return $this->namedDefinitions[$name];
        }

        // Apply autoloader

        $error = 'Type "%s" not found and could not be loaded';
        throw new TypeNotFoundException(sprintf($error, $name));
    }

    /**
     * @param Document $document
     * @return iterable
     */
    public function contextDefinitions(Document $document): iterable
    {
        return $this->context[$document->getId()] ?? [];
    }

    /**
     * @return iterable|Definition[]
     */
    public function allNamed(): iterable
    {
        foreach ($this->namedDefinitions as $definition) {
            yield $definition->getContext() => $definition;
        }
    }

    /**
     * @return iterable|Definition[]
     */
    public function allAnonymous(): iterable
    {
        foreach ($this->definitions as $definition) {
            yield $definition->getContext() => $definition;
        }
    }

    /**
     * @return iterable|Definition[]
     */
    public function all(): iterable
    {
        yield from $this->allNamed();
        yield from $this->allAnonymous();
    }
}
