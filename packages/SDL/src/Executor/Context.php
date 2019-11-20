<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Executor;

use Railt\SDL\Document;
use Railt\SDL\Ast\Node;
use Psr\Log\LoggerInterface;
use Phplrt\Visitor\Traverser;
use Railt\SDL\Builder\Factory;
use Railt\SDL\DocumentInterface;
use Railt\SDL\CompilerInterface;
use Phplrt\Contracts\Parser\ParserInterface;
use Phplrt\Contracts\Source\ReadableInterface;
use Railt\SDL\Executor\Linker\NamedTypeLinker;
use Railt\SDL\Executor\Registrar\TypeDefinition;
use Railt\SDL\Executor\Registrar\SchemaDefinition;
use Railt\SDL\Executor\Execution\DirectiveExecutor;
use Railt\SDL\Executor\Registrar\DirectiveDefinition;
use Railt\SDL\Executor\Linker\EnumTypeExtensionLinker;
use Railt\SDL\Executor\Linker\DirectiveExecutionLinker;
use Railt\SDL\Executor\Linker\UnionTypeExtensionLinker;
use Railt\SDL\Executor\Linker\ObjectTypeExtensionLinker;
use Railt\SDL\Executor\Linker\ScalarTypeExtensionLinker;
use Railt\SDL\Executor\Linker\SchemaTypeExtensionLinker;
use Railt\SDL\Executor\Extension\SchemaExtensionExecutor;
use Railt\SDL\Executor\Linker\InterfaceTypeExtensionLinker;
use Railt\SDL\Executor\Extension\EnumTypeExtensionExecutor;
use Railt\SDL\Executor\Extension\UnionTypeExtensionExecutor;
use Railt\SDL\Executor\Linker\InputObjectTypeExtensionLinker;
use Railt\SDL\Executor\Extension\ObjectTypeExtensionExecutor;
use Railt\SDL\Executor\Extension\ScalarTypeExtensionExecutor;
use Railt\SDL\Executor\Extension\InterfaceTypeExtensionExecutor;
use Railt\SDL\Executor\Extension\InputObjectTypeExtensionExecutor;
use Phplrt\Contracts\Parser\Exception\ParserRuntimeExceptionInterface;

/**
 * Class Context
 */
class Context
{
    /**
     * @var Document
     */
    private Document $document;

    /**
     * @var Registry
     */
    private Registry $registry;

    /**
     * @var ParserInterface
     */
    private ParserInterface $parser;

    /**
     * @var CompilerInterface
     */
    private CompilerInterface $compiler;

    /**
     * @var Factory
     */
    private Factory $factory;

    /**
     * @var LoggerInterface|null
     */
    private ?LoggerInterface $logger;

    /**
     * Context constructor.
     *
     * @param CompilerInterface $compiler
     * @param ParserInterface $parser
     * @param Document $document
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        CompilerInterface $compiler,
        ParserInterface $parser,
        Document $document,
        LoggerInterface $logger = null
    ) {
        $this->document = $document;
        $this->logger = $logger;
        $this->parser = $parser;
        $this->compiler = $compiler;

        $this->registry = new Registry();
        $this->factory = new Factory($this->document);
    }

    /**
     * @param string $message
     * @param mixed ...$args
     * @return bool
     */
    public function note(string $message, ...$args): bool
    {
        if ($this->logger) {
            $this->logger->debug(\vsprintf($message, $args));
        }

        return true;
    }

    /**
     * @return Document
     */
    public function getDocument(): Document
    {
        return $this->document;
    }

    /**
     * @return Registry
     */
    public function getRegistry(): Registry
    {
        return $this->registry;
    }

    /**
     * @param mixed|ReadableInterface|resource|string $source
     * @return DocumentInterface
     * @throws ParserRuntimeExceptionInterface
     */
    public function compile($source): DocumentInterface
    {
        return $this->run($this->parser->parse($source));
    }

    /**
     * ---------------------------------------------------------------------
     *  Type Extensions
     * ---------------------------------------------------------------------
     *
     *  Third tree walk:
     *  - Type Extension executions: We get each type extension and
     *      implement it in the finished assembly.
     *
     * @param iterable $ast
     * @return iterable
     */
    private function extendDefinitions(iterable $ast): iterable
    {
        return (new Traverser())
            ->with(new EnumTypeExtensionExecutor($this, $this->factory))
            ->with(new InputObjectTypeExtensionExecutor($this, $this->factory))
            ->with(new InterfaceTypeExtensionExecutor($this, $this->factory))
            ->with(new ObjectTypeExtensionExecutor($this, $this->factory))
            ->with(new ScalarTypeExtensionExecutor($this, $this->factory))
            ->with(new SchemaExtensionExecutor($this, $this->factory))
            ->with(new UnionTypeExtensionExecutor($this, $this->factory))
            ->traverse($ast);
    }

    /**
     * ---------------------------------------------------------------------
     *  Directive Executions
     * ---------------------------------------------------------------------
     *
     *  Last tree walk:
     *  - Directive executions: We get each directive execution and collect
     *      in the executions list.
     *
     * @param iterable $ast
     * @return iterable
     */
    private function executeDirectives(iterable $ast): iterable
    {
        return (new Traverser())
            ->with(new DirectiveExecutor($this->document))
            ->traverse($ast);
    }

    /**
     * Converts RL/SDL AST to a finite set of GraphQL types.
     *
     * @param iterable|Node[] $ast
     * @return DocumentInterface
     */
    public function run(iterable $ast): DocumentInterface
    {
        $ast = $this->registerDefinitions($ast);
        $ast = $this->resolveRelations($ast);

        $this->build();

        $ast = $this->extendDefinitions($ast);
        $ast = $this->executeDirectives($ast);

        return $this->document;
    }

    /**
     * ---------------------------------------------------------------------
     *  Registration
     * ---------------------------------------------------------------------
     *
     *  First tree walk:
     *  - Registering all types in the registry.
     *  - Verification that this type has not been previously
     *      registered in the dictionary (list of builded types)
     *      or registry (list of compiled types).
     *
     * @param iterable $ast
     * @return iterable
     */
    private function registerDefinitions(iterable $ast): iterable
    {
        return (new Traverser())
            ->with(new TypeDefinition($this))
            ->with(new SchemaDefinition($this))
            ->with(new DirectiveDefinition($this))
            ->traverse($ast);
    }

    /**
     * ---------------------------------------------------------------------
     *  Relations Resolving
     * ---------------------------------------------------------------------
     *
     *  Second tree walk:
     *  - Checks the types of the relationships.
     *  - Checks the types in expressions.
     *  - Loads missing types for correct compilation.
     *
     * @param iterable $ast
     * @return iterable
     */
    private function resolveRelations(iterable $ast): iterable
    {
        $loaders = $this->compiler->getAutoloaders();

        return (new Traverser())
            ->with(new DirectiveExecutionLinker($this, $loaders))
            ->with(new NamedTypeLinker($this, $loaders))
            ->with(new EnumTypeExtensionLinker($this, $loaders))
            ->with(new InputObjectTypeExtensionLinker($this, $loaders))
            ->with(new InterfaceTypeExtensionLinker($this, $loaders))
            ->with(new ObjectTypeExtensionLinker($this, $loaders))
            ->with(new ScalarTypeExtensionLinker($this, $loaders))
            ->with(new UnionTypeExtensionLinker($this, $loaders))
            ->with(new SchemaTypeExtensionLinker($this, $loaders))
            ->traverse($ast);
    }

    /**
     * ---------------------------------------------------------------------
     *  Building Final Structures
     * ---------------------------------------------------------------------
     *
     * Convert from AST to a set of finite DTO types.
     *
     * @return void
     */
    private function build(): void
    {
        $this->factory->loadFrom($this->registry);
    }
}
