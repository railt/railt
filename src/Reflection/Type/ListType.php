<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Reflection\Type;

use Hoa\Compiler\Llk\TreeNode;
use Serafim\Railgun\Compiler\Exceptions\TypeNotFoundException;
use Serafim\Railgun\Reflection\Abstraction\NamedDefinitionInterface;
use Serafim\Railgun\Reflection\Abstraction\Type\ListTypeInterface;
use Serafim\Railgun\Reflection\Abstraction\Type\RelationTypeInterface;
use Serafim\Railgun\Reflection\Document;

/**
 * Class ListType
 * @package Serafim\Railgun\Reflection\Type
 */
class ListType extends BaseType implements ListTypeInterface
{
    /**
     * @var RelationType
     */
    private $child;

    /**
     * ListType constructor.
     * @param Document $document
     * @param TreeNode $ast
     */
    public function __construct(Document $document, TreeNode $ast)
    {
        parent::__construct($document, $ast);

        /** @var TreeNode $child */
        foreach ($ast->getChildren() as $child) {
            if ($child->getId() === '#Type') {
                $this->child = new RelationType($document, $child);
            }
        }
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'List';
    }

    /**
     * @return NamedDefinitionInterface
     * @throws TypeNotFoundException
     */
    public function getRelationDefinition(): NamedDefinitionInterface
    {
        return $this->getChild()->getRelationDefinition();
    }

    /**
     * @return RelationTypeInterface
     */
    public function getChild(): RelationTypeInterface
    {
        return $this->child;
    }
}
