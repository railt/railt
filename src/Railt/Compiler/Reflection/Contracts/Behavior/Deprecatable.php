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
 * The interface indicates that the type can contain
 * information about the self-deprecation.
 *
 * The interface clearly contains these methods,
 * because directive "@deprecated" at the moment, it is
 * not regulated by the standard and the way of indication
 * may change in the future. That's why it's worth using this
 * interface, instead of getting information from the directive.
 */
interface Deprecatable
{
    /**
     * Returns a Boolean value indicating whether the type is deprecated.
     *
     * @return bool
     */
    public function isDeprecated(): bool;

    /**
     * Returns a String with information about why the type was
     * declared as deprecated. If the information is missing
     * for some reason - the method will return an empty line.
     *
     * @return string
     */
    public function getDeprecationReason(): string;
}
