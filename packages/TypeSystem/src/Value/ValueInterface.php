<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem\Value;

use Railt\Contracts\Common\StringableInterface;

/**
 * Interface ValueInterface
 */
interface ValueInterface extends StringableInterface
{
    /**
     * @param mixed $value
     * @return static
     */
    public static function parse($value): self;

    /**
     * @return mixed
     */
    public function toPHPValue();
}
