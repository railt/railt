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
use Railt\Reflection\Builder\DocumentBuilder;
use Railt\Reflection\Compiler\CompilerInterface;
use Railt\Reflection\Contracts\Behavior\Nameable;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Contracts\Types\TypeInterface;
use Railt\Reflection\Exceptions\BuildingException;

/**
 * Trait Builder
 * @mixin Compilable
 */
trait Builder
{
    use NameBuilder;
    use DirectivesBuilder;

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
     * @param TreeNode $ast
     * @return bool
     */
    public function compile(TreeNode $ast): bool
    {
        return false;
    }

    /**
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @return void
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    protected function bootBuilder(TreeNode $ast, DocumentBuilder $document): void
    {
        $this->ast = $ast;
        $this->document = $document;

        if ($this instanceof Nameable) {
            $this->precompileNameableType($ast);
        }
    }

    /**
     * @return self
     */
    final protected function resolve(): self
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
            if ($this instanceof TypeInterface) {
                // Initialize identifier
                $this->getUniqueId();
            }

            $siblings = \class_uses_recursive(static::class);

            foreach ($this->getAst()->getChildren() as $child) {
                if ($this->compileSiblings($siblings, $child)) {
                    continue;
                }

                if ($this->compile($child)) {
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
        \assert($this->ast instanceof TreeNode);

        return $this->ast;
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
     * @param TreeNode $ast
     * @return void
     * @throws BuildingException
     */
    protected function throwInvalidAstNodeError(TreeNode $ast): void
    {
        throw new BuildingException(\sprintf('Invalid %s AST Node.', $ast->getId()));
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
     * @return array
     */
    public function __debugInfo(): array
    {
        $result = [];

        foreach ($this->__sleep() as $param) {
            $result[$param] = $this->valueToString($this->$param);
        }

        return $result;
    }

    /**
     * @param $value
     * @return array|string
     */
    private function valueToString($value)
    {
        if ($value === null) {
            return null;
        }

        if (\is_scalar($value)) {
            return $value;
        }

        if (\is_iterable($value)) {
            $result = [];
            foreach ($value as $key => $sub) {
                $result[$key] = $this->valueToString($sub);
            }
            return $result;
        }

        return \get_class($value) . ' Object &' . \spl_object_hash($value) . '';
    }
}
