<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder\Support;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Builder\Compilable;
use Railt\Reflection\Builder\DocumentBuilder;
use Railt\Reflection\Compiler\CompilerInterface;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Exceptions\BuildingException;

/**
 * Trait WithCompiler
 * @mixin Compilable
 */
trait WithCompiler
{
    /**
     * @var TreeNode
     */
    protected $ast;

    /**
     * @var CompilerInterface
     */
    protected $compiler;

    /**
     * @var bool
     */
    private $completed = false;

    /**
     * SchemaBuilder constructor.
     * @param TreeNode $ast
     * @param Document|DocumentBuilder $document
     * @throws \Railt\Reflection\Exceptions\BuildingException
     */
    public function __construct(TreeNode $ast, Document $document)
    {
        $this->ast      = $ast;
        $this->document = $document;
        $this->compiler = $document->getCompiler();

        if (\method_exists($this, 'bootNamedBuilder')) {
            $this->bootNamedBuilder($ast);
        }
    }

    /**
     * @return CompilerInterface
     */
    public function getCompiler(): CompilerInterface
    {
        \assert($this->compiler !== null);

        return $this->compiler;
    }

    /**
     * @return $this|Compilable
     */
    protected function resolve(): Compilable
    {
        $this->compileIfNotCompiled();

        return $this;
    }

    /**
     * @return bool
     */
    public function compileIfNotCompiled(): bool
    {
        if ($this->completed === false) {
            $uses = \class_uses_recursive(static::class);

            foreach ($this->getAst()->getChildren() as $child) {
                if ($this->compile($child)) {
                    continue;
                }

                if ($this->compileSiblings($uses, $child)) {
                    continue;
                }
            }

            return $this->completed = true;
        }

        return false;
    }

    /**
     * @return TreeNode
     */
    public function getAst(): TreeNode
    {
        \assert($this->ast !== null);

        return $this->ast;
    }

    /**
     * @param TreeNode $ast
     * @return bool
     */
    public function compile(TreeNode $ast): bool
    {
        return false;
    }

    /**
     * @param array $siblings
     * @param TreeNode $child
     * @return bool
     */
    private function compileSiblings(array $siblings, TreeNode $child): bool
    {
        foreach ($siblings as $sibling) {
            $method = Compilable::ACTION_PREFIX . \class_basename($sibling);

            if (\method_exists($sibling, $method) && $this->$method($child)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param TreeNode $ast
     * @return void
     * @throws BuildingException
     */
    protected function throwInvalidAstNodeError(TreeNode $ast): void
    {
        throw new BuildingException(\sprintf('Invalid %s AST Node.', $ast->getId()));
    }
}
