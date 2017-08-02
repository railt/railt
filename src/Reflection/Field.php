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
use Serafim\Railgun\Reflection\Abstraction\DefinitionInterface;
use Serafim\Railgun\Reflection\Abstraction\DocumentTypeInterface;
use Serafim\Railgun\Reflection\Abstraction\FieldInterface;
use Serafim\Railgun\Reflection\Abstraction\NamedDefinitionInterface;
use Serafim\Railgun\Reflection\Abstraction\Type\ListTypeInterface;
use Serafim\Railgun\Reflection\Abstraction\Type\TypeInterface;
use Serafim\Railgun\Reflection\Common\Arguments;
use Serafim\Railgun\Reflection\Common\Directives;
use Serafim\Railgun\Reflection\Common\HasName;
use Serafim\Railgun\Reflection\Type\ListType;
use Serafim\Railgun\Reflection\Type\RelationType;

/**
 * Class Field
 * @package Serafim\Railgun\Reflection
 */
class Field extends Definition implements FieldInterface
{
    use HasName;
    use Arguments;
    use Directives;

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

        /** @var TreeNode $child */
        foreach ($ast->getChildren() as $child) {
            $this->compile($child);
        }
    }

    /**
     * @param TreeNode $ast
     */
    private function compile(TreeNode $ast): void
    {
        switch ($ast->getId()) {
            case '#List':
                $this->type = new ListType($this->document, $ast);
                break;
            case '#Type':
                $this->type = new RelationType($this->document, $ast);
                break;
        }
    }

    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface
    {
        return $this->type;
    }

    /**
     * @return NamedDefinitionInterface
     */
    public function getParentType(): NamedDefinitionInterface
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
     * @return bool
     */
    public function nonNull(): bool
    {
        return $this->getType()->nonNull();
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
    public function getRelationTypeName(): string
    {
        return $this->getRelationDefinition()->getTypeName();
    }

    /**
     * @return string
     */
    public function getRelationName(): string
    {
        return $this->getRelationDefinition()->getName();
    }


}
