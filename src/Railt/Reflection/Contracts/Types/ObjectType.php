<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Contracts\Types;

use Railt\Reflection\Contracts\Containers\HasFields;

/**
 * Interface ObjectType
 */
interface ObjectType extends HasFields, NamedTypeDefinition
{
    /**
     * @return iterable|InterfaceType[]
     */
    public function getInterfaces(): iterable;

    /**
     * @param string $name
     * @return bool
     */
    public function hasInterface(string $name): bool;

    /**
     * @param string $name
     * @return null|InterfaceType
     */
    public function getInterface(string $name): ?InterfaceType;

    /**
     * @return int
     */
    public function getNumberOfInterfaces(): int;
}
