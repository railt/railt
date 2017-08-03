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
use Illuminate\Support\Str;
use Serafim\Railgun\Compiler\Exceptions\TypeNotFoundException;
use Serafim\Railgun\Reflection\Abstraction\NamedDefinitionInterface;
use Serafim\Railgun\Reflection\Abstraction\Type\RelationTypeInterface;
use Serafim\Railgun\Reflection\Common\HasName;
use Serafim\Railgun\Reflection\Document;

/**
 * Class RelationType
 * @package Serafim\Railgun\Reflection\Type
 */
class RelationType extends BaseType implements RelationTypeInterface
{
    use HasName;

    /**
     * ScalarType constructor.
     * @param Document $document
     * @param TreeNode $ast
     */
    public function __construct(Document $document, TreeNode $ast)
    {
        parent::__construct($document, $ast);

        /** @var TreeNode $child */
        foreach ($ast->getChildren() as $child) {
            $matched = $child->getValueToken() === 'T_NAME' ||
                Str::startsWith($child->getValueToken(), 'T_SCALAR');

            if ($matched) {
                $this->name = $child->getValueValue();
            }
        }
    }

    /**
     * @return RelationTypeInterface
     */
    public function getChild(): RelationTypeInterface
    {
        return $this;
    }

    /**
     * @return string
     * @throws TypeNotFoundException
     */
    public function getTypeName(): string
    {
        return $this->getRelationDefinition()->getTypeName();
    }

    /**
     * @return NamedDefinitionInterface
     * @throws TypeNotFoundException
     */
    public function getRelationDefinition(): NamedDefinitionInterface
    {
        return $this->document->load($this->getName());
    }
}
