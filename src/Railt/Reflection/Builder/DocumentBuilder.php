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
use Railt\Reflection\Base\BaseDocument;
use Railt\Reflection\Builder\Support\Builder;
use Railt\Reflection\Builder\Support\Compilable;
use Railt\Reflection\Compiler\CompilerInterface;
use Railt\Reflection\Contracts\Behavior\Nameable;
use Railt\Reflection\Contracts\Types\SchemaType;
use Railt\Reflection\Contracts\Types\TypeDefinition;
use Railt\Reflection\Exceptions\BuildingException;
use Railt\Support\Filesystem\File;
use Railt\Support\Filesystem\ReadableInterface;

/**
 * Class DocumentBuilder
 */
class DocumentBuilder extends BaseDocument implements Compilable
{
    use Builder;

    /**
     *
     */
    public const VIRTUAL_FILE_NAME = 'SourceCode';

    /**
     *
     */
    public const AST_TYPE_MAPPING = [
        // Anonymous types
        '#SchemaDefinition'    => SchemaBuilder::class,

        // Named types
        '#ObjectDefinition'    => ObjectBuilder::class,
        '#InterfaceDefinition' => InterfaceBuilder::class,
        '#UnionDefinition'     => UnionBuilder::class,
        '#ScalarDefinition'    => ScalarBuilder::class,
        '#EnumDefinition'      => EnumBuilder::class,
        '#InputDefinition'     => InputBuilder::class,
        '#DirectiveDefinition' => DirectiveBuilder::class,

        // Modifiers
        '#ExtendDefinition'    => ExtendBuilder::class,
    ];

    /**
     * @var CompilerInterface
     */
    private $compiler;

    /**
     * DocumentBuilder constructor.
     * @param TreeNode $ast
     * @param ReadableInterface $readable
     * @throws CompilerException
     */
    public function __construct(TreeNode $ast, ReadableInterface $readable)
    {
        try {
            $this->name = $this->createName($readable);
            $this->bootBuilder($ast, $this);
        } catch (\Exception $exception) {
            throw new CompilerException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * @param CompilerInterface $compiler
     * @return DocumentBuilder
     */
    public function withCompiler(CompilerInterface $compiler): DocumentBuilder
    {
        $this->compiler = $compiler;

        return $this;
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

        /** @var Compilable|TypeDefinition $instance */
        $instance = new $class($ast, $this);

        switch (true) {
            case $instance instanceof SchemaType:
                $this->schema = $instance;
                break;

            case $instance instanceof Nameable:
                $this->types[$instance->getName()] = $instance;
                break;

            default:
                $this->types[] = $instance;
        }

        if ($instance instanceof TypeDefinition) {
            $this->compiler->register($instance);
        }

        return true;
    }

    /**
     * @return CompilerInterface
     */
    public function getCompiler(): CompilerInterface
    {
        return $this->compiler;
    }
}
