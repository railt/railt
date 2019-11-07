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
use Ramsey\Collection\Set;
use Phplrt\Visitor\Traverser;
use Railt\SDL\Builder\Factory;
use Railt\SDL\Parser\Generator;
use Railt\SDL\Executor\Registry;
use Phplrt\Contracts\Ast\NodeInterface;
use Railt\Contracts\SDL\CompilerInterface;
use Railt\Contracts\SDL\DocumentInterface;
use Phplrt\Contracts\Source\FileInterface;
use Phplrt\Contracts\Parser\ParserInterface;
use Phplrt\Source\Exception\NotFoundException;
use Railt\SDL\Executor\Linker\NamedTypeLinker;
use Phplrt\Contracts\Source\ReadableInterface;
use Railt\SDL\Executor\Registrar\TypeDefinition;
use GraphQL\Contracts\TypeSystem\SchemaInterface;
use Phplrt\Source\Exception\NotReadableException;
use Railt\SDL\Executor\Registrar\SchemaDefinition;
use GraphQL\Contracts\TypeSystem\DirectiveInterface;
use Railt\SDL\Executor\Registrar\DirectiveDefinition;
use Railt\SDL\Executor\Linker\EnumTypeExtensionLinker;
use Railt\SDL\Executor\Linker\DirectiveExecutionLinker;
use Railt\SDL\Executor\Linker\UnionTypeExtensionLinker;
use Railt\SDL\Executor\Linker\ObjectTypeExtensionLinker;
use Railt\SDL\Executor\Linker\ScalarTypeExtensionLinker;
use Railt\SDL\Executor\Linker\SchemaTypeExtensionLinker;
use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;
use Railt\SDL\Executor\Linker\InterfaceTypeExtensionLinker;
use Railt\SDL\Executor\Linker\InputObjectTypeExtensionLinker;
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
     * @var Set|callable[]
     */
    private Set $loaders;

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
        $this->loaders = new Set('callable');

        $this->loadSpec($spec);
    }

    /**
     * @return DocumentInterface
     */
    public function getDocument(): DocumentInterface
    {
        return $this->document;
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
        $dictionary ??= $this->document;

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
            ->traverse($ast);

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
            ->traverse($ast);

        /**
         * Last tree walk:
         *  - Convert from AST to a set of finite DTO types.
         */
        return (new Factory($dictionary))
            ->loadFrom($registry);
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
        if ($overwrite || ! $this->document->typeMap->containsKey($type->getName())) {
            $this->document->typeMap->put($type->getName(), $type);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function withDirective(DirectiveInterface $directive, bool $overwrite = false): self
    {
        if ($overwrite || ! $this->document->directives->containsKey($directive->getName())) {
            $this->document->directives->put($directive->getName(), $directive);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function withSchema(SchemaInterface $schema, bool $overwrite = false): self
    {
        if ($overwrite || ! $this->document->schema) {
            $this->document->schema = $schema;
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
        $this->loaders->add($loader);

        return $this;
    }

    /**
     * @param callable $loader
     * @return $this
     */
    public function cancelAutoload(callable $loader): self
    {
        $this->loaders->remove($loader);

        return $this;
    }
}
