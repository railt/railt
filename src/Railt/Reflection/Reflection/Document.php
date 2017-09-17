<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Reflection;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Parser\Exceptions\UnrecognizedTokenException;
use Railt\Reflection\Contracts\DocumentInterface;
use Railt\Reflection\Contracts\NamedDefinitionInterface;
use Railt\Reflection\Contracts\SchemaTypeInterface;
use Railt\Reflection\Dictionary;
use Railt\Reflection\Exceptions\TypeConflictException;
use Railt\Reflection\Exceptions\TypeNotFoundException;
use Railt\Reflection\Exceptions\UnrecognizedNodeException;
use Railt\Reflection\Reflection\Common\HasDefinitions;
use Railt\Reflection\Reflection\Common\HasLinkingStageInterface;
use Railt\Reflection\Reflection\Common\UniqueId;
use Railt\Support\Exceptions\NotReadableException;
use Railt\Support\Log\AllowsLoggerAddition;

/**
 * Class Document
 * @package Railt\Reflection\Reflection
 */
class Document extends Definition implements DocumentInterface
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
     * @throws TypeConflictException
     * @throws UnrecognizedNodeException
     * @throws \LogicException
     */
    public function __construct(string $fileName, TreeNode $ast, Dictionary $dictionary)
    {
        parent::__construct($this, $ast);

        $this->fileName   = $fileName;
        $this->dictionary = $dictionary;
    }

    /**
     * @throws \LogicException
     * @throws TypeConflictException
     * @throws UnrecognizedNodeException
     */
    public function compileChildren(): void
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

    /**
     * @throws TypeConflictException
     * @throws UnrecognizedNodeException
     */
    private function collectChildren(): void
    {
        foreach ($this->ast->getChildren() as $child) {
            $class = $this->resolveDefinition($child);

            /** @var Definition $definition */
            $definition = new $class($this, $child);
            $definition->withLogger($this->getLogger());

            $this->dictionary->register($definition);
        }
    }

    /**
     * @param TreeNode $ast
     * @return string
     * @throws UnrecognizedNodeException
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

        $message = UnrecognizedNodeException::DEFAULT_MESSAGE;
        throw UnrecognizedNodeException::new($message, $ast->getId());
    }

    /**
     * @return Dictionary
     */
    public function getDictionary(): Dictionary
    {
        return $this->dictionary;
    }

    /**
     * @param string $type
     * @return NamedDefinitionInterface
     * @throws NotReadableException
     * @throws TypeConflictException
     * @throws TypeNotFoundException
     * @throws UnrecognizedNodeException
     * @throws UnrecognizedTokenException
     * @throws \LogicException
     */
    public function load(string $type): NamedDefinitionInterface
    {
        $this->debug('Try to load type ' . $type);

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
