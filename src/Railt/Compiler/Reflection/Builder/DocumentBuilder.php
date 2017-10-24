<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Builder;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Compiler\Exceptions\CompilerException;
use Railt\Compiler\Reflection\Base\BaseDocument;
use Railt\Compiler\Reflection\Builder\Definitions;
use Railt\Compiler\Reflection\Builder\Process\Compilable;
use Railt\Compiler\Reflection\Builder\Process\Compiler;
use Railt\Compiler\Reflection\Builder\Processable\ExtendBuilder;
use Railt\Compiler\Reflection\CompilerInterface;
use Railt\Compiler\Reflection\Support;
use Railt\Compiler\Reflection\Contracts\Behavior\Nameable;
use Railt\Compiler\Reflection\Contracts\Definitions\SchemaDefinition;
use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Exceptions\BuildingException;
use Railt\Compiler\Exceptions\TypeRedefinitionException;
use Railt\Compiler\Filesystem\File;
use Railt\Compiler\Filesystem\ReadableInterface;

/**
 * Class DocumentBuilder
 */
class DocumentBuilder extends BaseDocument implements Compilable
{
    use Support;
    use Compiler;

    /**
     *
     */
    private const PHYSIC_FILE_NAME  = 'File(%s)';

    /**
     *
     */
    private const VIRTUAL_FILE_NAME = 'Source(%s)';

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
        if ($readable->getPathname() !== File::VIRTUAL_FILE_NAME) {
            return \sprintf(self::PHYSIC_FILE_NAME, \basename($readable->getPathname()));
        }

        return $this->createNameFromBacktrace();
    }

    /**
     * @return string
     */
    private function createNameFromBacktrace(): string
    {
        $trace = \array_reverse(\debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));

        $pointcut = self::VIRTUAL_FILE_NAME;

        foreach ($trace as $data) {
            $class = $data['class'] ?? null;

            if ($class === Compiler::class) {
                return \sprintf($pointcut, $class);
            }

            $pointcut = $class;
        }

        return \sprintf($pointcut, 'undefined');
    }

    /**
     * @param TreeNode $ast
     * @return bool
     * @throws \Railt\Compiler\Exceptions\TypeRedefinitionException
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
                $this->registerSchema($instance);
                break;

            case $this->isUniqueType($instance):
                $this->types[$instance->getName()] = $instance;
                $this->compiler->register($instance);
                break;

            default:
                $this->types[] = $instance;
        }

        return true;
    }

    /**
     * @param SchemaDefinition $schema
     * @return void
     * @throws TypeRedefinitionException
     */
    private function registerSchema(SchemaDefinition $schema): void
    {
        if ($this->schema !== null) {
            $error = \sprintf('Can not register a new %s. Schema already was defined.',
                $this->typeToString($schema));
            throw new TypeRedefinitionException($error);
        }

        $this->compiler->register($this->schema = $schema);
    }

    /**
     * @return CompilerInterface
     */
    public function getCompiler(): CompilerInterface
    {
        return $this->compiler;
    }
}
