<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Type;

use Hoa\Compiler\Llk\TreeNode;
use Illuminate\Support\Str;
use Railt\Reflection\Abstraction\NamedDefinitionInterface;
use Railt\Reflection\Abstraction\Type\RelationTypeInterface;
use Railt\Reflection\Common\HasName;
use Railt\Reflection\Document;

/**
 * Class RelationType
 * @package Railt\Reflection\Type
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
     * @throws \Railt\Exceptions\UnexpectedTokenException
     * @throws \Railt\Exceptions\UnrecognizedTokenException
     */
    public function getTypeName(): string
    {
        return $this->getRelationDefinition()->getTypeName();
    }

    /**
     * @return NamedDefinitionInterface
     * @throws \Railt\Exceptions\UnexpectedTokenException
     * @throws \Railt\Exceptions\UnrecognizedTokenException
     */
    public function getRelationDefinition(): NamedDefinitionInterface
    {
        return $this->document->load($this->getName());
    }
}
