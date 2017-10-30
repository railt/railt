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
use Railt\Compiler\Exceptions\BuildingException;
use Railt\Compiler\Exceptions\CompilerException;
use Railt\Compiler\Filesystem\ReadableInterface;
use Railt\Compiler\Reflection\Base\BaseDocument;
use Railt\Compiler\Reflection\Builder\Definitions;
use Railt\Compiler\Reflection\Builder\Process\Compilable;
use Railt\Compiler\Reflection\Builder\Process\Compiler;
use Railt\Compiler\Reflection\Builder\Processable\ExtendBuilder;
use Railt\Compiler\Compiler as CompilerEndpoint;
use Railt\Compiler\Reflection\CompilerInterface;
use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Compiler\Reflection\Support;

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
     *
     */
    private const PHYSIC_FILE_NAME = 'File(%s)';

    /**
     *
     */
    private const VIRTUAL_FILE_NAME = 'Source(%s)';

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
        $this->file = $readable;

        try {
            $this->name = $this->createName($readable);
            $this->bootBuilder($ast, $this);
        } catch (\Exception $exception) {
            throw new CompilerException($exception->getMessage(), $exception->getCode(), $exception);
        }

        $this->compileIfNotCompiled();
    }

    /**
     * @param ReadableInterface $readable
     * @return string
     */
    private function createName(ReadableInterface $readable): string
    {
        if ($readable->isFile()) {
            return \sprintf(self::PHYSIC_FILE_NAME, \basename($readable->getPathname()));
        }

        return \sprintf(self::VIRTUAL_FILE_NAME, $this->createNameFromBacktrace());
    }

    /**
     * @return string
     */
    private function createNameFromBacktrace(): string
    {
        $trace = \array_reverse(\debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));

        $previous = [];

        foreach ($trace as $data) {
            if (($data['class'] ?? null) === CompilerEndpoint::class) {
                return ($previous['class'] ?? $previous['file'] ?? 'undefined')
                    . ':' . ($previous['line'] ?? $data['line'] ?? 0);
            }

            $previous = $data;
        }

        return 'undefined';
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
     * @param TreeNode $ast
     * @return bool
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     * @throws \Railt\Compiler\Exceptions\TypeRedefinitionException
     * @throws BuildingException
     */
    public function compile(TreeNode $ast): bool
    {
        $class = self::AST_TYPE_MAPPING[$ast->getId()] ?? null;

        $this->verifyAst($class, $ast);

        /** @var Compilable|TypeDefinition $instance */
        $instance = new $class($ast, $this);

        $this->registerDefinition($instance);

        return true;
    }

    /**
     * @param Definition $definition
     * @return Definition|Definition[]
     * @throws \Railt\Compiler\Exceptions\TypeRedefinitionException
     */
    private function registerDefinition(Definition $definition)
    {
        if ($definition instanceof TypeDefinition) {
            return $this->types = $this->getValidator()->uniqueDefinitions($this->types, $definition);
        }

        return $this->definitions[] = $definition;
    }

    /**
     * @param null|string $class
     * @param TreeNode $ast
     * @return void
     * @throws BuildingException
     */
    private function verifyAst(?string $class, TreeNode $ast): void
    {
        if ($class === null) {
            $error = 'Broken abstract syntax tree, because a file %s can not contain an undefined Node %s';
            $error = \sprintf($error, $this->getName(), $ast->getId());

            throw new BuildingException($error);
        }
    }

    /**
     * @return CompilerInterface
     */
    public function getCompiler(): CompilerInterface
    {
        return $this->compiler;
    }
}
