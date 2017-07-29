<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Compiler\Reflection;

use Hoa\Compiler\Llk\TreeNode;
use Serafim\Railgun\Compiler\Autoloader;
use Serafim\Railgun\Compiler\Document;
use Serafim\Railgun\Compiler\Reflection\Support\NameResolvable;

/**
 * Interface DefinitionInterface
 * @package Serafim\Railgun\Compiler\Reflection
 */
abstract class Definition
{
    use NameResolvable;

    /**
     * @var TreeNode
     */
    protected $ast;

    /**
     * @var Document
     */
    private $parent;

    /**
     * @var bool
     */
    private $booted = false;

    /**
     * Definition constructor.
     * @param TreeNode $ast
     * @param Document $parent
     */
    public function __construct(TreeNode $ast, Document $parent)
    {
        $this->ast = $ast;
        $this->parent = $parent;

        $this->compileName($ast);
    }

    /**
     * @internal
     * @param TreeNode $node
     * @param Autoloader $loader
     * @return void
     */
    abstract public function compile(TreeNode $node, Autoloader $loader): void;

    /**
     * @internal
     * @return void
     */
    public function bootIfNotBooted(): void
    {
        if (!$this->booted) {
            $this->booted = true;
            foreach ($this->ast->getChildren() as $child) {
                $this->compile($child, $this->getContext()->getCompiler()->getLoader());
            }
        }
    }

    /**
     * @return string
     */
    abstract public static function getType(): string;

    /**
     * @return string
     */
    abstract public static function getAstId(): string;

    /**
     * @return TreeNode
     */
    public function getAst(): TreeNode
    {
        return $this->ast;
    }

    /**
     * @return Document
     */
    public function getContext(): Document
    {
        return $this->parent;
    }

    /**
     * @param null|string $name
     * @return Definition
     */
    public function rename(?string $name): Definition
    {
        $this->name = $name;

        return $this;
    }
}
