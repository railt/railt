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
 * Class FloatValue
 */
final class FloatValue extends Value
{
    /**
     * @var string
     */
    private const ERROR_INVALID_TYPE = 'GraphQL Float cannot represent a non-numeric value: %s';

    /**
     * @var float
     */
    public float $value;

    /**
     * FloatValueNode constructor.
     *
     * @param float $value
     */
    public function __construct(float $value)
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
        if (! \is_numeric($value)) {
            throw new \InvalidArgumentException(\sprintf(self::ERROR_INVALID_TYPE, $value));
        }

        return new static((float)$value);
    }

    /**
     * @return float
     */
    public function toPHPValue(): float
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return (string)$this->value;
    }
}
