<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Contracts\Containers;

use Railt\Reflection\Contracts\Types\TypeInterface;

/**
 * If the structure is a container that contains references to other any
 * types (eg. "Document" or "Union Type"), then it must implement this interface.
 *
 * If the structure contains any type of data that is strictly
 * typed (eg. Directives or Arguments), then type-specific interfaces should be used.
 */
interface HasTypes
{
    /**
     * This method should return a list of all types that are contained
     * in the this object.
     *
     * @return iterable|TypeInterface[] List of types.
     */
    public function getTypes(): iterable;

    /**
     * This method should return a Boolean value that indicates
     * the presence (or absence) of the corresponding type in this container.
     *
     * @param string $name The name of required type.
     * @return bool Presence of the type in the container.
     */
    public function hasType(string $name): bool;

    /**
     * This method should return the Type that is contained
     * in the container by type's name.
     *
     * @param string $name The name of required type.
     * @return null|TypeInterface The type object or null if there is no such Type in the container.
     */
    public function getType(string $name): ?TypeInterface;

    /**
     * Returns the number of types that the container contains.
     *
     * @return int
     */
    public function getNumberOfTypes(): int;
}
