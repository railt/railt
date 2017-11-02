<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Builder\Process;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Compiler\Reflection\Builder\DocumentBuilder;
use Railt\Compiler\Reflection\CompilerInterface;
use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Reflection\Contracts\Dependent\DependentDefinition;
use Railt\Compiler\Reflection\Contracts\Document;
use Railt\Compiler\Reflection\Validation\Validator;

/**
 * Trait Compiler
 * @mixin Compilable
 */
trait Compiler
{
    /**
     * @var TreeNode
     */
    private $ast;

    /**
     * @var array|string[]
     */
    private $siblingActions = [];

    /**
     * @var bool
     */
    private $completed = false;

    /**
     * @return void
     */
    public function compile(): void
    {
        if ($this->completed === false) {
            $this->completed = true;

            foreach ($this->getAst()->getChildren() as $child) {
                if ($this->compileSiblings($child)) {
                    continue;
                }

                if ($this->onCompile($child)) {
                    continue;
                }
            }
        }
    }

    /**
     * @return TreeNode
     */
    protected function getAst(): TreeNode
    {
        return $this->ast;
    }

    /**
     * @param TreeNode $child
     * @return bool
     */
    protected function compileSiblings(TreeNode $child): bool
    {
        foreach ($this->siblingActions as $action) {
            if ($this->$action($child)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Document|DocumentBuilder
     */
    public function getDocument(): Document
    {
        return $this->document;
    }

    /**
     * @return CompilerInterface
     */
    public function getCompiler(): CompilerInterface
    {
        return $this->getDocument()->getCompiler();
    }

    /**
     * @return Validator
     */
    public function getValidator(): Validator
    {
        return $this->getCompiler()->getValidator();
    }

    /**
     * @param TreeNode $ast
     * @param Document $document
     * @return void
     */
    protected function boot(TreeNode $ast, Document $document): void
    {
        $this->ast       = $ast;
        $this->document  = $document;

        // Generate identifier if id does not initialized
        $this->getUniqueId();

        // Collect sibling methods
        foreach (\class_uses_recursive(static::class) as $sibling) {
            $method = 'compile' . \class_basename($sibling);

            if (\method_exists($sibling, $method)) {
                $this->siblingActions[] = $method;
            }
        }

        /**
         * Initialize the name of the type, if it is an independent
         * unique definition of the type of GraphQL.
         */
        if ($this instanceof Definition) {
            $this->resolveTypeName();
        }

        /**
         * If the type is not initialized by the Document, but
         * is a child of the root, then the lazy assembly is not needed.
         *
         * In this case we run it forcibly, and then we check its state.
         */
        if ($this instanceof DependentDefinition) {
            // Force compile dependent definition
            $this->compile();

            // Verify type
            $this->getValidator()->verifyDefinition($this);
        }
    }

    /**
     * @param string $name
     * @param string $desc
     * @return void
     */
    private function resolveTypeName(string $name = '#Name', string $desc = '#Description'): void
    {
        /** @var TreeNode $child */
        foreach ($this->getAst()->getChildren() as $child) {
            switch ($child->getId()) {
                case $name:
                    $this->name = $child->getChild(0)->getValueValue();
                    break;

                case $desc:
                    $this->description = $this->parseDescription($child);
                    break;
            }
        }
    }

    /**
     * @param TreeNode $ast
     * @return string
     */
    private function parseDescription(TreeNode $ast): string
    {
        $description = \trim($ast->getChild(0)->getValueValue());

        return $description
            ? \preg_replace('/^\h*#?\h+(.*?)\h*$/imsu', '$1', $description)
            : $description;
    }

    /**
     * @param TreeNode $ast
     * @return bool
     */
    protected function onCompile(TreeNode $ast): bool
    {
        return false;
    }

    /**
     * @return void
     */
    public function __wakeup()
    {
        $this->completed = true;
    }

    /**
     * @param TreeNode $ast
     * @return string
     */
    protected function dump(TreeNode $ast): string
    {
        return $this->getCompiler()->getParser()->dump($ast);
    }
}
