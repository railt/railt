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
use Serafim\Railgun\Compiler\Exceptions\CompilerException;
use Serafim\Railgun\Compiler\Exceptions\SemanticException;
use Serafim\Railgun\Compiler\Reflection\Definition;
use Serafim\Railgun\Compiler\Reflection\SchemaDefinition;

/**
 * Class Document
 * @package Serafim\Railgun\Compiler
 */
class Document
{
    /**
     * @var int
     */
    private static $lastId = 0;

    /**
     * @var int
     */
    private $id = 0;

    /**
     * @var TreeNode
     */
    private $ast;

    /**
     * @var string
     */
    private $fileName;

    /**
     * @var Compiler
     */
    private $compiler;

    /**
     * Document constructor.
     * @param TreeNode $ast
     * @param string $fileName
     * @param Compiler $compiler
     * @throws SemanticException
     * @throws \Serafim\Railgun\Compiler\Exceptions\CompilerException
     */
    public function __construct(TreeNode $ast, string $fileName, Compiler $compiler)
    {
        $this->id = ++self::$lastId;

        $this->ast = $ast;
        $this->fileName = $fileName;
        $this->compiler = $compiler;

        $this->prepare();
    }

    /**
     * @return Compiler
     */
    public function getCompiler(): Compiler
    {
        return $this->compiler;
    }

    /**
     * @return void
     * @throws \Serafim\Railgun\Compiler\Exceptions\CompilerException
     * @throws SemanticException
     */
    private function prepare(): void
    {
        /** @var TreeNode $child */
        foreach ($this->ast->getChildren() as $child) {
            /** @var string|Definition|null $node */
            $node = $this->compiler->getRootNode($child);

            if ($node === null) {
                throw new CompilerException('Could not resolve AST type ' . $child->getId());
            }
            
            $instance = new $node($child, $this);

            $this->register($instance);
        }

        /** @var Definition $definition */
        foreach ($this->getDefinitions() as $definition) {
            $definition->bootIfNotBooted();
        }
    }

    /**
     * @param Definition $definition
     * @throws SemanticException
     */
    private function register(Definition $definition): void
    {
        $this->compiler->getDictionary()->register($definition);
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return iterable|Definition[]
     */
    public function getDefinitions(): iterable
    {
        return $this->compiler->getDictionary()
            ->contextDefinitions($this);
    }

    /**
     * @return null|SchemaDefinition
     */
    public function getSchema(): ?SchemaDefinition
    {
        foreach ($this->getDefinitions() as $definition) {
            if ($definition instanceof SchemaDefinition) {
                return $definition;
            }
        }

        return null;
    }

    /**
     * @return string
     */
    public function dump(): string
    {
        $result = (string)(new Dump())->visit($this->ast);

        $result = str_replace('>  ', '    ', $result);
        $result = preg_replace('/^\s{4}/ium', '', $result);

        return $result;
    }
}
