<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Abstraction;

use Railt\Reflection\Abstraction\Common\HasDirectivesInterface;

/**
 * Interface EnumTypeInterface
 * @package Railt\Reflection\Abstraction
 */
interface EnumTypeInterface extends
    NamedDefinitionInterface,
    HasDirectivesInterface
{
    /**
     * @return iterable|EnumValueInterface[]
     */
    public function getValues(): iterable;

    /**
     * @param string $name
     * @return bool
     */
    public function hasValue(string $name): bool;

    /**
     * @param string $name
     * @return null|EnumValueInterface
     */
    public function getValue(string $name): ?EnumValueInterface;
}
