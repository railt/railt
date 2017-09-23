<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Contracts\Types;

use Railt\Reflection\Contracts\Behavior\Inputable;
use Railt\Reflection\Contracts\Types\Enum\Value;

/**
 * Interface EnumType
 */
interface EnumType extends NamedTypeInterface, Inputable
{
    /**
     * @return iterable|Value[]
     */
    public function getValues(): iterable;

    /**
     * @param string $name
     * @return bool
     */
    public function hasValue(string $name): bool;

    /**
     * @param string $name
     * @return Value
     */
    public function getValue(string $name): ?Value;
}
