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
use Railt\Reflection\Abstraction\DefinitionInterface;
use Railt\Reflection\Abstraction\DocumentTypeInterface;
use Railt\Reflection\Abstraction\FieldInterface;
use Railt\Reflection\Abstraction\NamedDefinitionInterface;
use Railt\Reflection\Abstraction\Type\TypeInterface;
use Railt\Reflection\Common\Arguments;
use Railt\Reflection\Common\Directives;
use Railt\Reflection\Common\HasLinkingStageInterface;
use Railt\Reflection\Common\HasName;
use Railt\Reflection\Common\LinkingStage;
use Railt\Reflection\Type\ListType;
use Railt\Reflection\Type\RelationType;

/**
 * Class Field
 * @package Railt\Reflection
 */
class Field extends Definition implements
    HasLinkingStageInterface,
    FieldInterface
{
    use HasName;
    use Arguments;
    use Directives;
    use LinkingStage;

    /**
     * @var DefinitionInterface
     */
    private $parent;

    /**
     * @var TypeInterface
     */
    private $type;

    /**
     * Field constructor.
     * @param DocumentTypeInterface $document
     * @param TreeNode $ast
     * @param NamedDefinitionInterface $parent
     */
    public function __construct(DocumentTypeInterface $document, TreeNode $ast, NamedDefinitionInterface $parent)
    {
        parent::__construct($document, $ast);

        $this->parent = $parent;

        $this->bootHasName($document, $ast);
        $this->bootLinkingStage($document, $ast);

        $this->compileIfNotCompiled();
    }

    /**
     * @param Document $document
     * @param TreeNode $ast
     * @return TreeNode|null
     */
    public function compile(Document $document, TreeNode $ast): ?TreeNode
    {
        switch ($ast->getId()) {
            case '#List':
                $this->type = new ListType($this->document, $ast);
                break;
            case '#Type':
                $this->type = new RelationType($this->document, $ast);
                break;
        }

        return $ast;
    }

    /**
     * @return NamedDefinitionInterface
     */
    public function getParent(): NamedDefinitionInterface
    {
        return $this->parent;
    }

    /**
     * @return bool
     */
    public function isList(): bool
    {
        return $this->getType()->isList();
    }

    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function nonNull(): bool
    {
        return $this->getType()->nonNull();
    }

    /**
     * @return string
     */
    public function getRelationTypeName(): string
    {
        return $this->getRelationDefinition()->getTypeName();
    }

    /**
     * @return NamedDefinitionInterface
     */
    public function getRelationDefinition(): NamedDefinitionInterface
    {
        return $this->getType()->getRelationDefinition();
    }

    /**
     * @return string
     */
    public function getRelationName(): string
    {
        return $this->getRelationDefinition()->getName();
    }
}
