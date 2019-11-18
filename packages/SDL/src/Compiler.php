<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL;

use Phplrt\Source\File;
use Phplrt\Visitor\Traverser;
use Railt\SDL\Builder\Factory;
use Railt\SDL\Parser\Generator;
use Railt\SDL\Executor\Registry;
use Phplrt\Contracts\Ast\NodeInterface;
use Phplrt\Contracts\Source\FileInterface;
use Phplrt\Contracts\Parser\ParserInterface;
use Phplrt\Source\Exception\NotFoundException;
use Railt\SDL\Executor\Linker\NamedTypeLinker;
use Phplrt\Contracts\Source\ReadableInterface;
use Railt\SDL\Executor\Registrar\TypeDefinition;
use GraphQL\Contracts\TypeSystem\SchemaInterface;
use Phplrt\Source\Exception\NotReadableException;
use Railt\SDL\Executor\Registrar\SchemaDefinition;
use Railt\SDL\Executor\Execution\DirectiveExecutor;
use GraphQL\Contracts\TypeSystem\DirectiveInterface;
use Railt\SDL\Executor\Registrar\DirectiveDefinition;
use Railt\SDL\Executor\Linker\EnumTypeExtensionLinker;
use Railt\SDL\Executor\Linker\DirectiveExecutionLinker;
use Railt\SDL\Executor\Linker\UnionTypeExtensionLinker;
use Railt\SDL\Executor\Linker\ObjectTypeExtensionLinker;
use Railt\SDL\Executor\Linker\ScalarTypeExtensionLinker;
use Railt\SDL\Executor\Linker\SchemaTypeExtensionLinker;
use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;
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
 * Class Compiler
 */
final class Compiler implements CompilerInterface
{
    /**
     * @var int
     */
    public const SPEC_RAW = 0x00;

    /**
     * @var int
     */
    public const SPEC_JUNE_2018 = 0x02;

    /**
     * @var int
     */
    public const SPEC_RAILT = self::SPEC_JUNE_2018 | 0x04;

    /**
     * @var int
     */
    public const SPEC_INTROSPECTION = self::SPEC_JUNE_2018 | 0x08;

    /**
     * @var string[]
     */
    private const SPEC_MAPPINGS = [
        0x02 => __DIR__ . '/../resources/stdlib/stdlib.graphql',
        0x04 => __DIR__ . '/../resources/stdlib/extra.graphql',
        0x08 => __DIR__ . '/../resources/stdlib/introspection.graphql',
    ];

    /**
     * @var ParserInterface
     */
    private ParserInterface $parser;

    /**
     * @var Document
     */
    private Document $document;

    /**
     * @var array|callable[]
     */
    private array $loaders = [];

    /**
     * @var array|iterable[]
     */
    private array $cache = [];

    /**
     * Compiler constructor.
     *
     * @param int $spec
     * @param ParserInterface|null $parser
     * @throws NotFoundException
     * @throws NotReadableException
     * @throws \Throwable
     */
    public function __construct(int $spec = self::SPEC_RAILT, ParserInterface $parser = null)
    {
        $this->document = new Document();
        $this->parser = $parser ?? new Parser();

        $this->loadSpec($spec);
    }

    /**
     * @param int $spec
     * @return void
     * @throws NotFoundException
     * @throws NotReadableException
     * @throws \Throwable
     */
    private function loadSpec(int $spec): void
    {
        foreach (self::SPEC_MAPPINGS as $code => $file) {
            if (($spec & $code) === $code) {
                $this->preload(File::fromPathname($file));
            }
        }
    }

    /**
     * {@inheritDoc}
     * @throws \Throwable
     */
    public function preload($source): self
    {
        $this->build($this->parse($source), $this->document);

        return $this;
    }

    /**
     * Converts RL/SDL AST to a finite set of GraphQL types.
     *
     * @param iterable $ast
     * @param Document|null $dictionary
     * @return DocumentInterface
     */
    public function build(iterable $ast, Document $dictionary = null): DocumentInterface
    {
        $registry = new Registry();
        $factory = new Factory($dictionary ??= $this->document);

        /**
         * First tree walk:
         *  - Registering all types in the registry.
         *  - Verification that this type has not been previously
         *      registered in the dictionary (list of builded types)
         *      or registry (list of compiled types).
         */
        $ast = (new Traverser())
            ->with(new TypeDefinition($dictionary, $registry))
            ->with(new SchemaDefinition($dictionary, $registry))
            ->with(new DirectiveDefinition($dictionary, $registry))
            ->traverse($ast)
        ;

        /**
         * Second tree walk:
         *  - Checks the types of the relationships.
         *  - Checks the types in expressions.
         *  - Loads missing types for correct compilation.
         */
        $ast = (new Traverser())
            ->with(new DirectiveExecutionLinker($dictionary, $registry, $this->loaders))
            ->with(new NamedTypeLinker($dictionary, $registry, $this->loaders))
            ->with(new EnumTypeExtensionLinker($dictionary, $registry, $this->loaders))
            ->with(new InputObjectTypeExtensionLinker($dictionary, $registry, $this->loaders))
            ->with(new InterfaceTypeExtensionLinker($dictionary, $registry, $this->loaders))
            ->with(new ObjectTypeExtensionLinker($dictionary, $registry, $this->loaders))
            ->with(new ScalarTypeExtensionLinker($dictionary, $registry, $this->loaders))
            ->with(new UnionTypeExtensionLinker($dictionary, $registry, $this->loaders))
            ->with(new SchemaTypeExtensionLinker($dictionary, $registry, $this->loaders))
            ->traverse($ast)
        ;

        /**
         * Building.
         *  - Convert from AST to a set of finite DTO types.
         */
        $document = $factory->loadFrom($registry);

        /**
         * Third tree walk:
         *  - Type Extension executions: We get each type extension and
         *      implement it in the finished assembly.
         */
        $ast = (new Traverser())
            ->with(new EnumTypeExtensionExecutor($factory, $document, $registry))
            ->with(new InputObjectTypeExtensionExecutor($factory, $document, $registry))
            ->with(new InterfaceTypeExtensionExecutor($factory, $document, $registry))
            ->with(new ObjectTypeExtensionExecutor($factory, $document, $registry))
            ->with(new ScalarTypeExtensionExecutor($factory, $document, $registry))
            ->with(new SchemaExtensionExecutor($factory, $document, $registry))
            ->with(new UnionTypeExtensionExecutor($factory, $document, $registry))
            ->traverse($ast)
        ;

        /**
         * Last tree walk:
         *  - Directive executions: We get each directive execution and collect
         *      in the executions list.
         */
        $ast = (new Traverser())
            ->with(new DirectiveExecutor($document))
            ->traverse($ast)
        ;

        return $document;
    }

    /**
     * @param string|resource|ReadableInterface $source
     * @return array|iterable|NodeInterface|NodeInterface[]
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    private function parse($source): iterable
    {
        $source = File::new($source);

        return $this->cache[$this->hash($source)] ??= $this->parser->parse($source);
    }

    /**
     * @param ReadableInterface $source
     * @return string
     */
    private function hash(ReadableInterface $source): string
    {
        if ($source instanceof FileInterface) {
            return \md5_file($source->getPathname());
        }

        return \md5($source->getContents());
    }

    /**
     * @return void
     * @throws NotFoundException
     * @throws NotReadableException
     * @throws \Throwable
     */
    public function rebuild(): void
    {
        (new Generator())->generateAndSave();
    }

    /**
     * {@inheritDoc}
     */
    public function withType(NamedTypeInterface $type, bool $overwrite = false): self
    {
        if ($overwrite || ! $this->document->hasType($type->getName())) {
            $this->document->addType($type);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function withDirective(DirectiveInterface $directive, bool $overwrite = false): self
    {
        if ($overwrite || ! $this->document->hasDirective($directive->getName())) {
            $this->document->addDirective($directive);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function withSchema(SchemaInterface $schema, bool $overwrite = false): self
    {
        if ($overwrite || ! $this->document->getSchema()) {
            $this->document->setSchema($schema);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     * @throws \Throwable
     */
    public function compile($source): DocumentInterface
    {
        $pointcut = clone $this->document;

        $result = $this->build($this->parse($source), $this->document);

        $this->document = $pointcut;

        return $result;
    }

    /**
     * @param callable $loader
     * @return $this
     */
    public function autoload(callable $loader): self
    {
        $this->loaders[] = $loader;

        return $this;
    }

    /**
     * @param callable $loader
     * @return $this
     */
    public function cancelAutoload(callable $loader): self
    {
        $this->loaders = \array_filter($this->loaders, static function (callable $haystack) use ($loader): bool {
            return $haystack !== $loader;
        });

        return $this;
    }
}
