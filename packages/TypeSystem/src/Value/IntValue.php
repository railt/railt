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
 * Class IntValue
 */
final class IntValue extends Value
{
    /**
     * @var string
     */
    private const ERROR_INVALID_TYPE = 'GraphQL Int cannot represent a non-numeric value: %s';

    /**
     * @var string
     */
    private const ERROR_OVERFLOW = 'GraphQL Int cannot represent non 32-bit signed integer value: %s';

    /**
     * Note: -2 ** 31 = -2147483648
     *
     * @var int
     */
    public const MIN_INTEGER = -2147483648;

    /**
     * Note: 2 ** 31 = 2147483648
     *
     * @var int
     */
    public const MAX_INTEGER = 2147483648;

    /**
     * @var int
     */
    private int $value;

    /**
     * IntValue constructor.
     *
     * @param int $value
     */
    public function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * @param string $value
     * @return static
     * @throws \OverflowException
     * @throws \InvalidArgumentException
     */
    public static function parse($value): ValueInterface
    {
        if (! \is_numeric($value)) {
            throw new \InvalidArgumentException(\sprintf(self::ERROR_INVALID_TYPE, $value));
        }

        $integer = (int)$value;

        // As per the GraphQL Spec, Integers are only treated as valid when a valid
        // 32-bit signed integer, providing the broadest support across platforms.
        if ($integer >= self::MAX_INTEGER || $integer < self::MIN_INTEGER) {
            throw new \OverflowException(\sprintf(self::ERROR_OVERFLOW, $value));
        }

        return new static($integer);
    }

    /**
     * @return int
     */
    public function toPHPValue(): int
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
