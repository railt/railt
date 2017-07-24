<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Compiler;

use Hoa\Compiler\Llk\TreeNode;
use Hoa\Compiler\Visitor\Dump;

/**
 * Class Document
 * @package Serafim\Railgun\Compiler
 */
class Document
{
    /**
     * @var \SplFileInfo|null
     */
    private $file;

    /**
     * @var string
     */
    private $sources;

    /**
     * @var TreeNode
     */
    private $ast;

    /**
     * Definition constructor.
     * @param string $sources
     * @param TreeNode $ast
     * @param null|\SplFileInfo $file
     * @internal param $ ?\SplFileInfo $file
     */
    public function __construct(string $sources, TreeNode $ast, ?\SplFileInfo $file)
    {
        $this->file = $file;
        $this->sources = $sources;
        $this->ast = $ast;
    }

    /**
     * @return null|\SplFileInfo
     */
    public function getFile(): ?\SplFileInfo
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getSources(): string
    {
        return $this->sources;
    }

    /**
     * @return TreeNode
     */
    public function getAst(): TreeNode
    {
        return $this->ast;
    }

    /**
     * @return string
     */
    public function dump(): string
    {
        return (string)(new Dump())->visit($this->ast);
    }
}
