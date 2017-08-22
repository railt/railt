<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Compiler\Dictionary;
use Railt\Exceptions\IndeterminateBehaviorException;
use Railt\Reflection\Abstraction\DocumentTypeInterface;
use Railt\Reflection\Abstraction\NamedDefinitionInterface;
use Railt\Reflection\Abstraction\SchemaTypeInterface;
use Railt\Reflection\Common\HasDefinitions;
use Railt\Reflection\Common\HasLinkingStageInterface;
use Railt\Reflection\Common\UniqueId;

/**
 * Class Document
 * @package Railt\Reflection
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

        $message = 'Unrecognized AST node name %s: %s';
        throw IndeterminateBehaviorException::new($message, $ast->getId(), dump($ast));
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
