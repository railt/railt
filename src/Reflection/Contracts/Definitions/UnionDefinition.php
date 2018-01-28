<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Contracts\Definitions;

/**
 * Interface UnionDefinition
 */
interface UnionDefinition extends TypeDefinition
{
    /**
     * @return iterable|TypeDefinition[]
     */
    public function getTypeDefinitions(): iterable;

    /**
     * This method should return a Boolean value that indicates
     * the presence (or absence) of the corresponding type in this container.
     *
     * @param string $name The name of required type.
     * @return bool Presence of the type in the container.
     */
    public function hasTypeDefinition(string $name): bool;

    /**
     * This method should return the Type (except Union and Interface) that is contained
     * in the Union by type's name.
     *
     * @param string $name The name of required type.
     * @return null|TypeDefinition The type object or null if there is no such Type in the container.
     */
    public function getTypeDefinition(string $name): ?TypeDefinition;

    /**
     * Returns the number of types that the container contains.
     *
     * @return int
     */
    public function getNumberOfTypeDefinitions(): int;
}
