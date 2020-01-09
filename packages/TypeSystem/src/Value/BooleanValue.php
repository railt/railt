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
 * Class BooleanValue
 */
final class BooleanValue extends Value
{
    /**
     * @var bool
     */
    public bool $value;

    /**
     * BooleanValue constructor.
     *
     * @param bool $value
     */
    public function __construct(bool $value)
    {
        $this->value = $value;
    }

    /**
     * @param mixed $value
     * @return static
     * @throws \InvalidArgumentException
     */
    public static function parse($value): ValueInterface
    {
        return new static((bool)$value);
    }

    /**
     * @return bool
     */
    public function toPHPValue(): bool
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->value ? 'true' : 'false';
    }
}
