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
 * Class NullValue
 */
final class NullValue extends Value
{
    /**
     * NullValue constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param mixed $value
     * @return static
     * @throws \InvalidArgumentException
     */
    public static function parse($value): ValueInterface
    {
        return new static();
    }

    /**
     * @return mixed|null
     */
    public function toPHPValue()
    {
        return null;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return 'null';
    }
}
