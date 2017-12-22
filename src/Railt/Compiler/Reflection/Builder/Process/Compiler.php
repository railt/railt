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
use Railt\Compiler\Reflection\Validation\Base\ValidatorInterface;
use Railt\Compiler\Reflection\Validation\Definitions;
use Railt\Compiler\Reflection\Validation\Uniqueness;
use Railt\Reflection\Contracts\Definitions\Definition;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Contracts\Dependent\DependentDefinition;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Filesystem\File;

/**
 * Trait Compiler
 * @mixin Compilable
 */
trait Compiler
{
    /**
     * @var int
     */
    protected $offset = 0;

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

            /** @var TreeNode $child */
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
    public function getAst(): TreeNode
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
     * @param string $group
     * @return ValidatorInterface
     * @throws \OutOfBoundsException
     */
    public function getValidator(string $group = null): ValidatorInterface
    {
        return $this->getCompiler()->getValidator($group);
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        $result = ['offset'];

        if (\method_exists(parent::class, '__sleep')) {
            return \array_merge(parent::__sleep(), $result);
        }

        return $result;
    }

    /**
     * @return void
     */
    public function __wakeup(): void
    {
        $this->completed = true;
    }

    /**
     * @return int
     */
    public function getDeclarationLine(): int
    {
        return $this->getDeclarationInfo()['line'] ?? 1;
    }

    /**
     * @return array
     */
    private function getDeclarationInfo(): array
    {
        return File::getErrorInfo($this->getDocument()->getContents(), $this->offset);
    }

    /**
     * @return int
     */
    public function getDeclarationColumn(): int
    {
        return $this->getDeclarationInfo()['column'] ?? 0;
    }

    /**
     * @param TreeNode $ast
     * @param Document $document
     * @return void
     */
    protected function boot(TreeNode $ast, Document $document): void
    {
        $this->ast      = $ast;
        $this->document = $document;

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
            $this->getCompiler()->getStack()->push($this);

            // Force compile dependent definition
            $this->compile();

            // Verify type
            $this->getValidator(Definitions::class)->validate($this);

            $this->getCompiler()->getStack()->pop();
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
                    $node                        = $child->getChild(0);
                    [$this->name, $this->offset] = [$node->getValueValue(), $node->getOffset()];
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
     * @param string $type
     * @return TypeDefinition
     */
    protected function load(string $type): TypeDefinition
    {
        return $this->getCompiler()->get($type);
    }

    /**
     * @param array|TypeDefinition|null $field
     * @param TypeDefinition $definition
     * @return TypeDefinition|array
     */
    protected function unique($field, TypeDefinition $definition)
    {
        $this->getValidator(Uniqueness::class)->validate($field, $definition);

        if (\is_array($field)) {
            $field[$definition->getName()] = $definition;

            return $field;
        }

        return $definition;
    }

    /**
     * @param TreeNode $ast
     * @return string
     */
    protected function dump(TreeNode $ast): string
    {
        return $this->getCompiler()->getParser()->dump($ast);
    }

    /**
     * @param string $keyword
     * @return int
     */
    private function offsetPrefixedBy(string $keyword): int
    {
        return $this->offset - \strlen($keyword) - 1;
    }
}
