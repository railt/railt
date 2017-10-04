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
 * The interface indicates that the parent
 * type is a child of another type.
 */
interface Child
{
    /**
     * Returns a reference to the parent type.
     *
     * @return Nameable
     */
    public function getParent(): Nameable;
}
