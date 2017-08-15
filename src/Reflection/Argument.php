<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Reflection;

use Hoa\Compiler\Llk\TreeNode;
use Railgun\Reflection\Abstraction\ArgumentInterface;
use Railgun\Reflection\Abstraction\DocumentTypeInterface;
use Railgun\Reflection\Abstraction\NamedDefinitionInterface;
use Railgun\Reflection\Abstraction\Type\TypeInterface;
use Railgun\Reflection\Common\Directives;
use Railgun\Reflection\Common\HasLinkingStageInterface;
use Railgun\Reflection\Common\HasName;
use Railgun\Reflection\Common\LinkingStage;
use Railgun\Reflection\Type\ListType;
use Railgun\Reflection\Type\RelationType;

/**
 * Class Argument
 * @package Railgun\Reflection
 */
class Argument extends Definition implements
    HasLinkingStageInterface,
    ArgumentInterface
{
    use HasName;
    use Directives;
    use LinkingStage;

    /**
     * @var NamedDefinitionInterface
     */
    private $parent;

    /**
     * @var TypeInterface
     */
    private $type;

    /**
     * @var mixed
     */
    private $defaultValue;

    /**
     * @var bool
     */
    private $hasDefaultValue = false;

    /**
     * Argument constructor.
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
     * @return TypeInterface
     */
    public function getType(): TypeInterface
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function hasDefaultValue(): bool
    {
        return $this->hasDefaultValue;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @return NamedDefinitionInterface
     */
    public function getParent(): NamedDefinitionInterface
    {
        return $this->parent;
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
            case '#Value':
                $this->hasDefaultValue = true;
                $this->defaultValue = Value::new($document, $ast);
        }

        return $ast;
    }
}
