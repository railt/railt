<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Parser\Exceptions\CompilerException;
use Railt\Reflection\Builder\Support\Identifier;
use Railt\Reflection\Compiler\CompilerInterface;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Contracts\Types\NamedTypeInterface;
use Railt\Reflection\Contracts\Types\SchemaType;
use Railt\Reflection\Contracts\Types\TypeInterface;
use Railt\Reflection\Exceptions\BuildingException;
use Railt\Support\Filesystem\File;
use Railt\Support\Filesystem\ReadableInterface;

/**
 * Class DocumentBuilder
 */
class DocumentBuilder extends AbstractTypeBuilder implements Document
{
    /**
     *
     */
    public const VIRTUAL_FILE_NAME = 'SourceCode';

    /**
     *
     */
    private const AST_TYPE_MAPPING = [
        '#SchemaDefinition'    => SchemaBuilder::class,
        '#ObjectDefinition'    => ObjectBuilder::class,
        '#InterfaceDefinition' => InterfaceBuilder::class,
        // '#UnionDefinition'     => UnionBuilder::class,
        // '#ScalarDefinition'    => ScalarBuilder::class,
        // '#EnumDefinition'      => EnumBuilder::class,
        // '#InputDefinition'     => InputBuilder::class,
        // '#ExtendDefinition'    => ExtendBuilder::class,
        // '#DirectiveDefinition' => DirectiveBuilder::class,
    ];

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var CompilerInterface
     */
    private $compiler;

    /**
     * DocumentBuilder constructor.
     * @param TreeNode $ast
     * @param ReadableInterface $readable
     * @param CompilerInterface $compiler
     * @throws CompilerException
     */
    public function __construct(TreeNode $ast, ReadableInterface $readable, CompilerInterface $compiler)
    {
        $this->compiler = $compiler;

        try {
            $this->id = Identifier::generate();
            $this->name = $this->createName($readable);
        } catch (\Exception $e) {
            throw new CompilerException($e->getMessage(), $e->getCode(), $e);
        }

        parent::__construct($ast, $this);
    }

    /**
     * @param ReadableInterface $readable
     * @return string
     */
    private function createName(ReadableInterface $readable): string
    {
        return $readable->getPathname() === File::VIRTUAL_FILE_NAME
            ? static::VIRTUAL_FILE_NAME
            : \sprintf('File(%s)', \basename($readable->getPathname()));
    }

    /**
     * @return CompilerInterface
     */
    public function getCompiler(): CompilerInterface
    {
        return $this->compiler;
    }

    /**
     * @param TreeNode $ast
     * @return bool
     * @throws BuildingException
     */
    public function compile(TreeNode $ast): bool
    {
        $class = self::AST_TYPE_MAPPING[$ast->getId()] ?? null;

        if ($class === null) {
            $this->throwInvalidAstNodeError($ast);
        }

        /** @var AbstractTypeBuilder $instance */
        $instance = new $class($ast, $this);

        $this->getCompiler()->register($instance);

        return true;
    }

    /**
     * @return null|SchemaType|TypeInterface
     */
    public function getSchema(): ?SchemaType
    {
        $this->compileIfNotCompiled();

        return $this->compiler->get(SchemaBuilder::class, $this);
    }

    /**
     * @return iterable|TypeInterface[]|NamedTypeInterface[]
     */
    public function getTypes(): iterable
    {
        $this->compileIfNotCompiled();

        return $this->compiler->all($this);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasType(string $name): bool
    {
        $this->compileIfNotCompiled();

        return $this->compiler->has($name, $this);
    }

    /**
     * @param string $name
     * @return null|TypeInterface
     */
    public function getType(string $name): ?TypeInterface
    {
        $this->compileIfNotCompiled();

        return $this->compiler->get($name, $this);
    }

    /**
     * @return int
     */
    public function getNumberOfTypes(): int
    {
        $this->compileIfNotCompiled();

        return \count($this->compiler->all($this));
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return \sprintf('%s<%s>', $this->getName(), $this->getUniqueId());
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUniqueId(): string
    {
        return $this->id;
    }
}
