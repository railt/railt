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
use Hoa\Compiler\Visitor\Dump;
use Serafim\Railgun\Compiler\Autoloader;
use Serafim\Railgun\Compiler\Dictionary;
use Serafim\Railgun\Compiler\Document;
use Serafim\Railgun\Compiler\Reflection\Support\HasName;

/**
 * Interface DefinitionInterface
 * @package Serafim\Railgun\Compiler\Reflection
 */
abstract class Definition
{
    use HasName;

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
     * @param Dictionary $dictionary
     */
    abstract public function compile(TreeNode $node, Dictionary $dictionary): void;

    /**
     * @internal
     * @return void
     */
    public function bootIfNotBooted(): void
    {
        if (!$this->booted) {
            $this->booted = true;
            foreach ($this->ast->getChildren() as $child) {
                $this->compile($child, $this->getContext()->getCompiler()->getDictionary());
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

    /**
     * TODO Remove it in future
     * @param TreeNode $node
     * @param bool $die
     */
    protected function dump(TreeNode $node, bool $die = true): void
    {
        var_dump((new Dump())->visit($node));
        if ($die) {
            die;
        }
    }
}
