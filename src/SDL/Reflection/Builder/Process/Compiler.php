<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection\Builder\Process;

use Railt\Compiler\Ast\NodeInterface;
use Railt\Compiler\Ast\RuleInterface;
use Railt\Io\File;
use Railt\Reflection\Contracts\Definitions\Definition;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Contracts\Dependent\DependentDefinition;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Contracts\Invocations\Invocable;
use Railt\SDL\Reflection\Builder\DocumentBuilder;
use Railt\SDL\Reflection\CompilerInterface;
use Railt\SDL\Reflection\Validation\Base\ValidatorInterface;
use Railt\SDL\Reflection\Validation\Definitions;
use Railt\SDL\Reflection\Validation\Uniqueness;

/**
 * Trait Compiler
 */
trait Compiler
{
    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * @var NodeInterface
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
     * @return NodeInterface|RuleInterface
     */
    public function getAst(): NodeInterface
    {
        return $this->ast;
    }

    /**
     * @param NodeInterface $child
     * @return bool
     */
    protected function compileSiblings(NodeInterface $child): bool
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
     * @param NodeInterface $ast
     * @param Document $document
     * @return void
     */
    protected function boot(NodeInterface $ast, Document $document): void
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

        if ($this instanceof Compilable && $this instanceof Invocable) {
            $this->getDocument()->future($this);
            return;
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

            // Normalize types
            $this->getCompiler()->normalize($this);

            // Verify type
            $this->getValidator(Definitions::class)->validate($this);

            $this->getCompiler()->getStack()->pop();
        }
    }

    /**
     * @param NodeInterface $ast
     * @param string $type
     * @param array $path
     * @return array|float|int|null|string
     */
    protected function parseValue(NodeInterface $ast, string $type, array $path = [])
    {
        return $this->getDocument()->getValueBuilder()->parse($ast, $type, $path);
    }

    /**
     * @param string $name
     * @param string $desc
     * @return void
     */
    private function resolveTypeName(string $name = '#Name', string $desc = '#Description'): void
    {
        foreach ($this->getAst()->getChildren() as $child) {
            switch ($child->getName()) {
                case $name:
                    $node                        = $child->getChild(0);
                    [$this->name, $this->offset] = [$node->getValue(), $node->getOffset()];
                    break;

                case $desc:
                    $this->description = $this->parseDescription($child);
                    break;
            }
        }
    }

    /**
     * @param NodeInterface|RuleInterface $ast
     * @return string
     */
    private function parseDescription(NodeInterface $ast): string
    {
        $description = \trim($ast->getChild(0)->getValue());

        return $description
            ? \preg_replace('/^\h*#?\h+(.*?)\h*$/imsu', '$1', $description)
            : $description;
    }

    /**
     * @param NodeInterface $ast
     * @return bool
     */
    protected function onCompile(NodeInterface $ast): bool
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
     * @param NodeInterface $ast
     * @return string
     */
    protected function dump(NodeInterface $ast): string
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
