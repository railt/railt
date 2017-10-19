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
use Railt\Reflection\Builder\Definitions;
use Railt\Reflection\Builder\Process\Compilable;
use Railt\Reflection\Builder\Process\Compiler;
use Railt\Reflection\Builder\Processable\ExtendBuilder;
use Railt\Reflection\Compiler\CompilerInterface;
use Railt\Reflection\Contracts\Behavior\Nameable;
use Railt\Reflection\Contracts\Definitions\SchemaDefinition;
use Railt\Reflection\Contracts\Definitions\Definition;
use Railt\Reflection\Exceptions\BuildingException;
use Railt\Support\Filesystem\File;
use Railt\Support\Filesystem\ReadableInterface;

/**
 * Class DocumentBuilder
 */
class DocumentBuilder extends BaseDocument implements Compilable
{
    use Compiler;

    /**
     *
     */
    public const VIRTUAL_FILE_NAME = 'SourceCode';

    /**
     *
     */
    public const AST_TYPE_MAPPING = [
        // Anonymous types
        '#SchemaDefinition'    => Definitions\SchemaBuilder::class,

        // Named types
        '#ObjectDefinition'    => Definitions\ObjectBuilder::class,
        '#InterfaceDefinition' => Definitions\InterfaceBuilder::class,
        '#UnionDefinition'     => Definitions\UnionBuilder::class,
        '#ScalarDefinition'    => Definitions\ScalarBuilder::class,
        '#EnumDefinition'      => Definitions\EnumBuilder::class,
        '#InputDefinition'     => Definitions\InputBuilder::class,
        '#DirectiveDefinition' => Definitions\DirectiveBuilder::class,

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

        /** @var Compilable|Definition $instance */
        $instance = new $class($ast, $this);

        switch (true) {
            case $instance instanceof SchemaDefinition:
                $this->schema = $instance;
                break;

            case $instance instanceof Nameable:
                $this->types[$instance->getName()] = $instance;
                break;

            default:
                $this->types[] = $instance;
        }

        if ($instance instanceof Definition) {
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
