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
use Serafim\Railgun\Compiler\Document;

/**
 * Interface DefinitionInterface
 * @package Serafim\Railgun\Compiler\Reflection
 */
abstract class Definition
{
    /**
     * @var string|null
     */
    private $name;

    /**
     * @var TreeNode
     */
    protected $ast;

    /**
     * @var Document
     */
    private $parent;

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
     * @param TreeNode $ast
     */
    private function compileName(TreeNode $ast): void
    {
        $name = $ast->getChild(0);

        if ($name && $name->getId() === '#Name') {
            $this->name = $name->getChild(0)->getValueValue();
        }
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
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
