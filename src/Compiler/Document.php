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
use Serafim\Railgun\Compiler\Reflection\Definition;
use Serafim\Railgun\Compiler\Reflection\Dictionary;
use Serafim\Railgun\Compiler\Reflection\Schema;

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
     * @var Dictionary
     */
    private $dictionary;

    /**
     * @var string[]|Definition[]
     */
    private $definitions = [];

    /**
     * Definition constructor.
     * @param string $sources
     * @param TreeNode $ast
     * @param null|\SplFileInfo $file
     * @throws \RuntimeException
     */
    public function __construct(string $sources, TreeNode $ast, ?\SplFileInfo $file)
    {
        $this->ast = $ast;
        $this->file = $file;
        $this->sources = $sources;
        $this->dictionary = new Dictionary();

        $this->prepare();
        $this->build();
    }

    /**
     * @return void
     * @throws \RuntimeException
     */
    private function prepare(): void
    {
        $definitions = [
            Schema::class,
        ];

        try {
            foreach ($definitions as $definition) {
                $this->addDefinition($definition);
            }
        } catch (\InvalidArgumentException $e) {
            throw new \RuntimeException('Error while initializing document reflection');
        }
    }

    /**
     * @param string[]|Definition[] ...$classes
     * @return Document|$this
     * @throws \InvalidArgumentException
     */
    public function addDefinition(string ...$classes): Document
    {
        foreach ($classes as $class) {
            if (!is_subclass_of($class, Definition::class, true)) {
                throw new \InvalidArgumentException($class . ' must be instance of ' . Definition::class . ' class');
            }

            $this->definitions[$class::getAstNodeId()] = $class;
        }

        return $this;
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
        $result = (string)(new Dump())->visit($this->ast);

        $result = str_replace('>  ', '    ', $result);
        $result = preg_replace('/^\s{4}/ium', '', $result);

        return $result;
    }

    private function build(): void
    {
        /** @var TreeNode $child */
        foreach ($this->ast->getChildren() as $child) {
            $name = $child->getId();

            if (!isset($this->definitions[$name])) {
                throw new \OutOfRangeException('Invalid AST node name ' . $name);
            }

            $this->dictionary->push($this->buildValue($this->definitions[$name], $child));

        }
    }

    /**
     * @param string|Definition $definition
     * @param TreeNode $node
     * @return array
     */
    private function buildValue(string $definition, TreeNode $node): array
    {
        return [
            'ast'  => [
                'name'   => $node->getId(),
                'value'  => $node->getValue(),
                'parent' => [
                    'name'  => $node->getParent()->getId(),
                    'value' => $node->getParent()->getValue(),
                ],
            ],
            'type' => new $definition($node, $this->dictionary),
        ];
    }
}
