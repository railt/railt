<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Abstraction;

use Railt\Reflection\Abstraction\Common\HasDescription;
use Railt\Reflection\Abstraction\Common\HasDirectivesInterface;
use Railt\Reflection\Abstraction\Common\HasFieldsInterface;

/**
 * Interface ObjectTypeInterface
 * @package Railt\Reflection\Abstraction
 */
interface ObjectTypeInterface extends
    NamedDefinitionInterface,
    HasDirectivesInterface,
    HasFieldsInterface,
    HasDescription
{
    /**
     * @return iterable|InterfaceTypeInterface[]
     */
    public function getInterfaces(): iterable;

    /**
     * @param string $name
     * @return bool
     */
    public function hasInterface(string $name): bool;

    /**
     * @param string $name
     * @return null|InterfaceTypeInterface
     */
    public function getInterface(string $name): ?InterfaceTypeInterface;
}
