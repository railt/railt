<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Phplrt\Contracts\Source\ReadableInterface;
use Phplrt\Source\File;
use Railt\SDL\Compiler\Command\CommandInterface;
use Railt\SDL\Dictionary;
use Railt\SDL\Exception\TypeAlreadyDefinedException;
use Railt\SDL\Exception\TypeNotFoundException;
use Railt\SDL\Node\Expression\Expression;
use Railt\SDL\Node\NodeInterface;
use Railt\TypeSystem\DefinitionInterface;
use Railt\TypeSystem\DirectiveDefinition;
use Railt\TypeSystem\NamedTypeDefinition;
use Railt\TypeSystem\SchemaDefinition;
use Railt\TypeSystem\TypeInterface;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL
 *
 * @template-implements \IteratorAggregate<array-key, CommandInterface>
 */
final class Context implements \IteratorAggregate
{
    private readonly ConstExprEvaluator $expr;

    /**
     * @param \Closure(ReadableInterface,Context):void $process
     */
    public function __construct(
        private Queue $queue,
        private readonly Dictionary $dictionary,
        private readonly TypeLoader $loader,
        private readonly \Closure $process,
    ) {
        $this->expr = new ConstExprEvaluator($this->queue);
    }

    public function push(CommandInterface $command): void
    {
        $this->queue->push($command);
    }

    public function eval(TypeInterface $type, Expression $expr): mixed
    {
        return $this->expr->eval($type, $expr);
    }

    public function setSchema(SchemaDefinition $schema, NodeInterface $node): void
    {
        if ($this->dictionary->findSchema()) {
            throw TypeAlreadyDefinedException::fromSchema($node->getSource(), $node->getPosition());
        }

        $this->dictionary->setSchema($schema);
    }

    public function getSchema(NodeInterface $node): SchemaDefinition
    {
        $schema = $this->dictionary->findSchema();

        if ($schema === null) {
            throw TypeNotFoundException::fromTypeName('schema', $node->getSource(), $node->getPosition());
        }

        return $schema;
    }

    public function addType(NamedTypeDefinition $type, NodeInterface $node): void
    {
        if ($this->dictionary->hasType($type->getName())) {
            throw TypeAlreadyDefinedException::fromTypeName(
                $type->getName(),
                $node->getSource(),
                $node->getPosition(),
            );
        }

        $this->dictionary->addType($type);
    }

    /**
     * @param non-empty-string $name
     */
    public function getType(string $name, NodeInterface $node, DefinitionInterface $from = null): NamedTypeDefinition
    {
        if (!$this->dictionary->hasType($name)) {
            $source = ($this->loader)($name, $from);

            if ($source !== null) {
                ($this->process)(File::new($source), $this);
            }
        }

        $result = $this->dictionary->findType($name);

        if ($result === null) {
            throw TypeNotFoundException::fromTypeName($name, $node->getSource(), $node->getPosition());
        }

        return $result;
    }

    public function addDirective(DirectiveDefinition $directive, NodeInterface $node): void
    {
        if ($this->dictionary->hasDirective($directive->getName())) {
            throw TypeAlreadyDefinedException::fromDirectiveName(
                $directive->getName(),
                $node->getSource(),
                $node->getPosition(),
            );
        }

        $this->dictionary->addDirective($directive);
    }

    /**
     * @param non-empty-string $name
     */
    public function getDirective(
        string $name,
        NodeInterface $node,
        DefinitionInterface $from = null
    ): DirectiveDefinition {
        if (!$this->dictionary->hasDirective($name)) {
            $source = ($this->loader)($name, $from);

            if ($source !== null) {
                ($this->process)(File::new($source), $this);
            }
        }

        $result = $this->dictionary->findDirective($name);

        if ($result === null) {
            throw TypeNotFoundException::fromDirectiveName($name, $node->getSource(), $node->getPosition());
        }

        return $result;
    }

    public function getIterator(): \Traversable
    {
        return $this->queue;
    }

    public function __clone()
    {
        $this->queue = new Queue();
    }
}
