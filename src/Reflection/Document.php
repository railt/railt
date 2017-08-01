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
use Serafim\Railgun\Compiler\Exceptions\TypeNotFoundException;
use Serafim\Railgun\Reflection\Abstraction\DocumentTypeInterface;
use Serafim\Railgun\Reflection\Abstraction\NamedDefinitionInterface;
use Serafim\Railgun\Reflection\Abstraction\SchemaTypeInterface;
use Serafim\Railgun\Reflection\Common\HasDefinitions;
use Serafim\Railgun\Reflection\Common\HasUniqueId;

/**
 * Class Document
 * @package Serafim\Railgun\Reflection
 */
class Document extends Definition implements DocumentTypeInterface
{
    use HasUniqueId;
    use HasDefinitions;

    /**
     * @var null|string
     */
    private $fileName;

    /**
     * @var TreeNode
     */
    private $ast;

    /**
     * @var SchemaTypeInterface|null
     */
    private $schema;

    /**
     * Document constructor.
     * @param null|string $fileName
     * @param TreeNode $ast
     * @param Dictionary $dictionary
     */
    public function __construct(?string $fileName, TreeNode $ast, Dictionary $dictionary)
    {
        parent::__construct($this, $ast);

        $this->ast = $ast;
        $this->fileName = $fileName;
        $this->dictionary = $dictionary;

        $this->compileIfNotBooted($dictionary);
        $this->compileChildren();
    }

    /**
     * @param string $type
     * @return NamedDefinitionInterface
     * @throws TypeNotFoundException
     */
    public function load(string $type): NamedDefinitionInterface
    {
        return $this->dictionary->get($type);
    }

    /**
     * @param TreeNode $ast
     * @param Dictionary $dictionary
     * @throws \LogicException
     */
    protected function compile(TreeNode $ast, Dictionary $dictionary): void
    {
        /** @var Definition $class */
        $class = $this->resolveDefinition($ast);

        $definition = new $class($this, $ast);

        $this->dictionary->register($definition);
    }

    /**
     * @param TreeNode $ast
     * @return string
     * @throws \LogicException
     */
    private function resolveDefinition(TreeNode $ast): string
    {
        switch ($ast->getId()) {
            case '#SchemaDefinition':
                return SchemaDefinition::class;
            case '#ObjectDefinition':
                return ObjectDefinition::class;
            case '#InterfaceDefinition':
                return InterfaceDefinition::class;
            case '#UnionDefinition':
                return UnionDefinition::class;
            case '#ScalarDefinition':
                return ScalarDefinition::class;
            case '#EnumDefinition':
                return EnumDefinition::class;
            case '#InputDefinition':
                return InputDefinition::class;
            case '#ExtendDefinition':
                return ExtendDefinition::class;
            case '#DirectiveDefinition':
                return DirectiveDefinition::class;
        }

        throw new \LogicException('Unrecognized AST node name ' . $ast->getId());
    }

    /**
     * @return void
     */
    private function compileChildren(): void
    {
        /** @var Definition $definition */
        foreach ($this->getDefinitions() as $definition) {
            $definition->compileIfNotBooted($this->dictionary);

            if ($definition instanceof SchemaTypeInterface) {
                $this->schema = $definition;
            }
        }
    }

    /**
     * @return null|string
     */
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * @return null|SchemaTypeInterface
     */
    public function getSchema(): ?SchemaTypeInterface
    {
        return $this->schema;
    }
}
