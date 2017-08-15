<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Reflection\Abstraction\Type;

use Railgun\Reflection\Abstraction\NamedDefinitionInterface;

/**
 * Interface TypeInterface
 * @package Railgun\Reflection\Abstraction\Type
 */
interface TypeInterface
{
    /**
     * @return bool
     */
    public function nonNull(): bool;

    /**
     * Parent is:
     *  - ListType: $this->getChild()->getRelationName()
     *  - RelationType: "as is"
     *
     * @return string
     */
    public function getRelationName(): string;

    /**
     * Parent is:
     *  - ListType: $this->getChild()->getRelationDefinition()
     *  - RelationType: "as is"
     *
     * @return NamedDefinitionInterface
     */
    public function getRelationDefinition(): NamedDefinitionInterface;

    /**
     * Returns "$this instanceof ListType"
     *
     * @return bool
     */
    public function isList(): bool;

    /**
     * Parent is:
     *  - ListType: return Relation child node, like "as is"
     *  - RelationType: return $this
     *
     * @return RelationTypeInterface
     */
    public function getChild(): RelationTypeInterface;
}
