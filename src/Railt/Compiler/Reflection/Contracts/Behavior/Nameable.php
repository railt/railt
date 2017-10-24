<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Contracts\Behavior;

/**
 * The Interface indicates that a type instance can have a
 * unique unique name that uniquely identifies it in the system.
 */
interface Nameable
{
    /**
     * Returns the name of type instance.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Returns a short description of type.
     *
     * @return string
     */
    public function getDescription(): string;
}
