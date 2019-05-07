<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Contracts\Behavior;

/**
 * Interface Inputable
 */
interface Inputable
{
    /**
     * @param mixed $value
     * @return bool
     */
    public function isCompatible($value): bool;
}
