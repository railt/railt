<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Reflection;

use Hoa\Compiler\Llk\TreeNode;
use Serafim\Railgun\Compiler\Dictionary;
use Serafim\Railgun\Reflection\Abstraction\DefinitionInterface;
use Serafim\Railgun\Reflection\Abstraction\DocumentTypeInterface;
use Serafim\Railgun\Reflection\Abstraction\NamedDefinitionInterface;

/**
 * Class Definition
 * @package Serafim\Railgun\Reflection
 */
abstract class Definition implements DefinitionInterface
{
    /**
     * @var Document
     */
    private $document;

    /**
     * @var bool
     */
    protected $compiled = false;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var array|callable[]
     */
    private $beforeCompile = [];

    /**
     * @var TreeNode
     */
    private $ast;

    /**
     * Definition constructor.
     * @param Document $document
     * @param TreeNode $ast
     */
    public function __construct(Document $document, TreeNode $ast)
    {
        $this->ast = $ast;
        $this->document = $document;

        if ($this instanceof NamedDefinitionInterface && $ast->getChild(0)->getId() === '#Name') {
            $this->name = $ast->getChild(0)->getValueValue();
        }

        foreach (class_uses_recursive($this) as $trait) {
            $name = 'compile' . class_basename($trait);
            if (method_exists($this, $name)) {
                $this->beforeCompile[] = [$this, $name];
            }
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string)$this->name;
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return class_basename(static::class);
    }

    /**
     * @param TreeNode $ast
     * @param Dictionary $dictionary
     * @return null|TreeNode
     */
    abstract protected function compile(TreeNode $ast, Dictionary $dictionary): ?TreeNode;

    /**
     * @param Dictionary $dictionary
     * @return bool
     */
    public function compileIfNotBooted(Dictionary $dictionary): bool
    {
        if ($this->compiled) {
            return false;
        }

        foreach ($this->ast->getChildren() as $child) {
            $redefined = $this->compile($child, $dictionary);

            if ($redefined instanceof TreeNode) {
                $child = $redefined;
            }

            foreach ($this->beforeCompile as $callable) {
                call_user_func($callable, $child, $dictionary);
            }

        }

        return $this->compiled = true;
    }

    /**
     * @return DocumentTypeInterface
     */
    public function getDocument(): DocumentTypeInterface
    {
        return $this->document;
    }
}
