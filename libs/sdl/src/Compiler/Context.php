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
use Railt\TypeSystem\Definition\DirectiveDefinition;
use Railt\TypeSystem\Definition\NamedTypeDefinition;
use Railt\TypeSystem\Definition\SchemaDefinition;
use Railt\TypeSystem\DefinitionInterface;
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
     * @param array<non-empty-string, mixed> $variables
     */
    public function __construct(
        array $variables,
        private Queue $queue,
        private readonly Dictionary $dictionary,
        public readonly ConfigInfo $config,
        private readonly TypeLoader $loader,
        private readonly \Closure $process,
    ) {
        $this->expr = new ConstExprEvaluator($this->queue, $variables, $this->config);
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
        if ($this->dictionary->findSchemaDefinition()) {
            throw TypeAlreadyDefinedException::fromSchema($node->getSource(), $node->getPosition());
        }

        $this->dictionary->setSchemaDefinition($schema);
    }

    public function getSchema(NodeInterface $node): SchemaDefinition
    {
        $schema = $this->dictionary->findSchemaDefinition();

        if ($schema === null) {
            throw TypeNotFoundException::fromTypeName('schema', $node->getSource(), $node->getPosition());
        }

        return $schema;
    }

    public function addType(NamedTypeDefinition $type, NodeInterface $node): void
    {
        if ($this->dictionary->hasTypeDefinition($type->getName())) {
            throw TypeAlreadyDefinedException::fromTypeName(
                $type->getName(),
                $node->getSource(),
                $node->getPosition(),
            );
        }

        $this->dictionary->addTypeDefinition($type);
    }

    /**
     * @param non-empty-string $name
     */
    public function getType(string $name, NodeInterface $node, DefinitionInterface $from = null): NamedTypeDefinition
    {
        if (!$this->dictionary->hasTypeDefinition($name)) {
            $source = ($this->loader)($name, $from);

            if ($source !== null) {
                ($this->process)(File::new($source), $this);
            }
        }

        $result = $this->dictionary->findTypeDefinition($name);

        if ($result === null) {
            throw TypeNotFoundException::fromTypeName($name, $node->getSource(), $node->getPosition());
        }

        return $result;
    }

    public function addDirective(DirectiveDefinition $directive, NodeInterface $node): void
    {
        if ($this->dictionary->hasDirectiveDefinition($directive->getName())) {
            throw TypeAlreadyDefinedException::fromDirectiveName(
                $directive->getName(),
                $node->getSource(),
                $node->getPosition(),
            );
        }

        $this->dictionary->addDirectiveDefinition($directive);
    }

    /**
     * @param non-empty-string $name
     */
    public function getDirective(
        string $name,
        NodeInterface $node,
        DefinitionInterface $from = null
    ): DirectiveDefinition {
        if (!$this->dictionary->hasDirectiveDefinition($name)) {
            $source = ($this->loader)('@' . $name, $from);

            if ($source !== null) {
                ($this->process)(File::new($source), $this);
            }
        }

        $result = $this->dictionary->findDirectiveDefinition($name);

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
