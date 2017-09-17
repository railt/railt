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
use Railt\Reflection\Contracts\ArgumentInterface;
use Railt\Reflection\Contracts\DocumentInterface;
use Railt\Reflection\Contracts\NamedDefinitionInterface;
use Railt\Reflection\Contracts\Type\TypeInterface;
use Railt\Reflection\Exceptions\BrokenAstException;
use Railt\Reflection\Reflection\Common\Directives;
use Railt\Reflection\Reflection\Common\HasDescription;
use Railt\Reflection\Reflection\Common\HasLinkingStageInterface;
use Railt\Reflection\Reflection\Common\HasName;
use Railt\Reflection\Reflection\Common\LinkingStage;
use Railt\Reflection\Reflection\Type\ListType;
use Railt\Reflection\Reflection\Type\RelationType;

/**
 * Class Argument
 * @package Railt\Reflection\Reflection
 */
class Argument extends Definition implements
    HasLinkingStageInterface,
    ArgumentInterface
{
    use HasName;
    use Directives;
    use LinkingStage;
    use HasDescription;

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
     * @param DocumentInterface $document
     * @param TreeNode $ast
     * @param NamedDefinitionInterface $parent
     * @throws \LogicException
     */
    public function __construct(DocumentInterface $document, TreeNode $ast, NamedDefinitionInterface $parent)
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
     * @throws BrokenAstException
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
                $this->defaultValue    = Value::new($document, $ast);
        }

        return $ast;
    }
}
