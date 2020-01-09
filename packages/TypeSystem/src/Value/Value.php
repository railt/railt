<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem\Value;

/**
 * Class Value
 */
abstract class Value implements ValueInterface
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}
