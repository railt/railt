<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Builder;

use Railt\Reflection\Support;
use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Base\BaseDocument;
use Railt\Compiler\Exceptions\BuildingException;
use Railt\Compiler\Exceptions\CompilerException;
use Railt\Compiler\Reflection\CompilerInterface;
use Railt\Reflection\Filesystem\ReadableInterface;
use Railt\Compiler\Exceptions\TypeConflictException;
use Railt\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Reflection\Builder\Process\Compiler;
use Railt\Compiler\Reflection\Builder\Process\Compilable;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Compiler\Reflection\Builder\Processable\ExtendBuilder;

/**
 * Class DocumentBuilder.
 */
class DocumentBuilder extends BaseDocument implements Compilable
{
    use Support;
    use Compiler;

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
     * @param CompilerInterface $compiler
     * @throws CompilerException
     */
    public function __construct(TreeNode $ast, ReadableInterface $readable, CompilerInterface $compiler)
    {
        $this->compiler = $compiler;
        $this->file = $readable;

        $this->compiler->getStack()->push($this);

        try {
            $this->boot($ast, $this);
            $this->name = $readable->getPathname();
        } catch (\Exception $fatal) {
            throw CompilerException::wrap($fatal);
        }

        $this->compile();
    }

    /**
     * @param CompilerInterface $compiler
     * @return DocumentBuilder
     */
    public function withCompiler(CompilerInterface $compiler): self
    {
        $this->compiler = $compiler;

        return $this;
    }

    /**
     * @return CompilerInterface
     */
    final public function getCompiler(): CompilerInterface
    {
        return $this->compiler;
    }

    /**
     * @param TreeNode $ast
     * @return bool
     * @throws \OutOfBoundsException
     * @throws TypeConflictException
     * @throws BuildingException
     */
    protected function onCompile(TreeNode $ast): bool
    {
        $class = self::AST_TYPE_MAPPING[$ast->getId()] ?? null;

        $this->verifyAst($class, $ast);

        /** @var Compilable|TypeDefinition $instance */
        $instance = new $class($ast, $this);

        $this->registerDefinition($instance);

        return true;
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

            throw new BuildingException($error, $this->getCompiler()->getStack());
        }
    }

    /**
     * @param Definition $definition
     * @return Definition|Definition[]
     * @throws \OutOfBoundsException
     */
    private function registerDefinition(Definition $definition)
    {
        if ($definition instanceof TypeDefinition) {
            return $this->types = $this->unique($this->types, $definition);
        }

        return $this->definitions[] = $definition;
    }
}
