<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Contracts\Behavior;

use Railt\Reflection\Contracts\Types\EnumType;
use Railt\Reflection\Contracts\Types\InputType;
use Railt\Reflection\Contracts\Types\NamedTypeInterface;
use Railt\Reflection\Contracts\Types\ScalarType;

/**
 * Interface AllowsTypeIndication
 */
interface AllowsTypeIndication
{
    /**
     * @return NamedTypeInterface
     */
    public function getType(): NamedTypeInterface;

    /**
     * @return bool
     */
    public function isList(): bool;

    /**
     * @return bool
     */
    public function isNonNull(): bool;

    /**
     * @return bool
     */
    public function isNonNullList(): bool;

    /**
     * @param AllowsTypeIndication $type
     * @return bool
     */
    public function canBeOverridenBy(AllowsTypeIndication $type): bool;
}
