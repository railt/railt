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
use Serafim\Railgun\Exceptions\IndeterminateBehaviorException;
use Serafim\Railgun\Reflection\Abstraction\DocumentTypeInterface;
use Serafim\Railgun\Reflection\Abstraction\NamedDefinitionInterface;
use Serafim\Railgun\Reflection\Abstraction\SchemaTypeInterface;
use Serafim\Railgun\Reflection\Common\HasDefinitions;
use Serafim\Railgun\Reflection\Common\HasLinkingStageInterface;
use Serafim\Railgun\Reflection\Common\UniqueId;

/**
 * Class Document
 * @package Serafim\Railgun\Reflection
 */
class Document extends Definition implements DocumentTypeInterface
{
    use UniqueId;
    use HasDefinitions;

    /**
     * @var null|string
     */
    private $fileName;

    /**
     * @var SchemaTypeInterface|null
     */
    private $schema;

    /**
     * @var Dictionary
     */
    private $dictionary;

    /**
     * Document constructor.
     * @param string $fileName
     * @param TreeNode $ast
     * @param Dictionary $dictionary
     */
    public function __construct(string $fileName, TreeNode $ast, Dictionary $dictionary)
    {
        parent::__construct($this, $ast);

        $this->fileName = $fileName;
        $this->dictionary = $dictionary;

        $this->compileChildren();
    }

    /**
     *
     */
    private function compileChildren(): void
    {
        $this->collectChildren();

        /** @var Definition $definition */
        foreach ($this->getDefinitions() as $definition) {
            if ($definition instanceof HasLinkingStageInterface) {
                $definition->compileIfNotCompiled();
            }

            if ($definition instanceof SchemaTypeInterface) {
                $this->schema = $definition;
            }
        }
    }

    private function collectChildren(): void
    {
        foreach ($this->ast->getChildren() as $child) {
            /** @var Definition $class */
            $class = $this->resolveDefinition($child);

            $definition = new $class($this, $child);

            $this->dictionary->register($definition);
        }
    }

    /**
     * @param TreeNode $ast
     * @return string
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

        throw IndeterminateBehaviorException::make('Unrecognized AST node name %s', $ast->getId());
    }

    /**
     * @return Dictionary
     */
    public function getDictionary(): Dictionary
    {
        return $this->dictionary;
    }

    public function load(string $type): NamedDefinitionInterface
    {
        return $this->dictionary->find($type);
    }

    /**
     * @return null|SchemaTypeInterface
     */
    public function getSchema(): ?SchemaTypeInterface
    {
        return $this->schema;
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return sprintf('Document<%s>', basename((string)$this->getFileName()));
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @return bool
     */
    public function isStdlib(): bool
    {
        return false;
    }
}
