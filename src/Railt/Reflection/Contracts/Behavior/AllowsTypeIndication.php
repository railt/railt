<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Contracts\Behavior;

/**
 * Interface AllowsTypeIndication
 */
interface AllowsTypeIndication
{
    /**
     * @return Inputable
     */
    public function getType(): Inputable;

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
}
