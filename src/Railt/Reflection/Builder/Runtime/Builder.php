<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder\Runtime;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Builder\AbstractTypeBuilder;
use Railt\Reflection\Builder\Compilable;
use Railt\Reflection\Builder\DocumentBuilder;
use Railt\Reflection\Compiler\CompilerInterface;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Exceptions\BuildingException;

/**
 * Trait Builder
 * @mixin Compilable
 */
trait Builder
{
    /**
     * @var TreeNode
     */
    private $ast;

    /**
     * @var Document|DocumentBuilder
     */
    private $document;

    /**
     * @var bool
     */
    private $compilationCompleted = false;

    /**
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @return void
     */
    protected function bootBuilder(TreeNode $ast, DocumentBuilder $document): void
    {
        $this->ast      = $ast;
        $this->document = $document;
    }

    /**
     * @return CompilerInterface
     */
    public function getCompiler(): CompilerInterface
    {
        return $this->document->getCompiler();
    }

    /**
     * @return Document|DocumentBuilder
     */
    public function getDocument(): Document
    {
        return $this->document;
    }

    /**
     * @return $this|self
     */
    protected function compiled(): self
    {
        $this->compileIfNotCompiled();

        return $this;
    }

    /**
     * @return bool
     */
    public function compileIfNotCompiled(): bool
    {
        if ($this->compilationCompleted === false) {
            $uses = \class_uses_recursive(static::class);

            foreach ($this->getAst()->getChildren() as $child) {
                if ($this->compile($child)) {
                    continue;
                }

                if ($this->compileSiblings($uses, $child)) {
                    continue;
                }
            }

            return $this->compilationCompleted = true;
        }

        return false;
    }

    /**
     * @return TreeNode
     */
    public function getAst(): TreeNode
    {
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
