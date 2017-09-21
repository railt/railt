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
use Railt\Reflection\Compiler\CompilerInterface;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Exceptions\BuildingException;

/**
 * Class AbstractBuilder
 */
abstract class AbstractBuilder implements Compilable
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
     * TypeBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document)
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
     * @return AbstractTypeBuilder|$this
     */
    protected function compiled(): AbstractTypeBuilder
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
        $error = 'Invalid %s AST Node.';

        throw new BuildingException(\sprintf($error, $ast->getId()));
    }
}
