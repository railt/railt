<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Contracts\Definitions;

use Railt\Component\SDL\Contracts\Dependent\Field\HasFields;
use Railt\Component\SDL\Contracts\Invocations\Directive\HasDirectives;

/**
 * Interface ObjectDefinition
 */
interface ObjectDefinition extends TypeDefinition, HasFields, HasDirectives
{
    /**
     * @return iterable|InterfaceDefinition[]
     */
    public function getInterfaces(): iterable;

    /**
     * @param string $name
     * @return bool
     */
    public function hasInterface(string $name): bool;

    /**
     * @param string $name
     * @return null|InterfaceDefinition
     */
    public function getInterface(string $name): ?InterfaceDefinition;

    /**
     * @return int
     */
    public function getNumberOfInterfaces(): int;
}
