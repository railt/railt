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
use Railt\Compiler\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Compiler\Reflection\Contracts\Dependent\DependentDefinition;
use Railt\Compiler\Reflection\Contracts\Document;
use Railt\Compiler\Reflection\Validation\Validator;

/**
 * Trait Builder
 */
trait Compiler
{
    use NameBuilder;

    /**
     * @var TreeNode
     */
    protected $ast;

    /**
     * @var bool
     */
    protected $completed = false;

    /**
     * @return Document|DocumentBuilder
     */
    public function getDocument(): Document
    {
        \assert($this->document instanceof Document);

        return $this->document;
    }

    /**
     * @return CompilerInterface
     */
    public function getCompiler(): CompilerInterface
    {
        \assert($this->getDocument()->getCompiler() instanceof CompilerInterface);

        return $this->getDocument()->getCompiler();
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        $this->compileIfNotCompiled();

        $data = ['completed'];

        if (\method_exists(parent::class, '__sleep')) {
            return \array_merge(parent::__sleep(), $data);
        }

        return $data;
    }

    /**
     * @return bool
     */
    public function compileIfNotCompiled(): bool
    {
        if ($this->completed === false) {
            $this->completed = true;

            /**
             * Initialize definition Unique Identifier.
             */
            if ($this instanceof Definition) {
                $this->getUniqueId();
            }

            /**
             * Boot compile-step traits.
             */
            $siblings = \class_uses_recursive(static::class);

            foreach ($this->getAst()->getChildren() as $child) {
                if ($this->compileSiblings($siblings, $child)) {
                    continue;
                }

                if ($this->compile($child)) {
                    continue;
                }
            }

            return true;
        }

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
            $method = 'compile' . \class_basename($sibling);

            if (\method_exists($sibling, $method) && $this->$method($child)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return TreeNode
     */
    public function getAst(): TreeNode
    {
        \assert($this->ast instanceof TreeNode);

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
     * @return Validator
     */
    protected function getValidator(): Validator
    {
        return $this->getCompiler()->getValidator();
    }

    /**
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @return void
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    protected function bootBuilder(TreeNode $ast, DocumentBuilder $document): void
    {
        $this->ast = $ast;
        $this->document = $document;

        /**
         * Initialize the name of the type, if it is an independent
         * unique definition of the type of GraphQL.
         */
        if ($this instanceof TypeDefinition) {
            /** @var $this NameBuilder */
            $this->precompileNameableType($ast);
        }

        /**
         * If the type is not initialized by the Document, but
         * is a child of the root, then the lazy assembly is not needed.
         *
         * In this case we run it forcibly, and then we check its state.
         */
        if ($this instanceof DependentDefinition) {
            // Compile
            $this->compileIfNotCompiled();

            // Verify
            $this->getValidator()->verifyDefinition($this);
        }
    }
}
