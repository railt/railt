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
 * Class EnumValue
 */
final class EnumValue extends Value
{
    /**
     * @var string[]
     */
    private const RESERVED_CHARACTERS = ['true', 'false', 'null'];

    /**
     * @var string
     */
    private const ERROR_INVALID_TYPE = 'GraphQL EnumValue cannot represent a non-string value of type %s';

    /**
     * @var string
     */
    private const ERROR_INVALID_FORMAT = '%s is an invalid value of GraphQL EnumValue';

    /**
     * @var string
     */
    private const ERROR_RESERVED = 'GraphQL EnumValue cannot represent a reserved value %s';

    /**
     * @var string
     */
    private string $value;

    /**
     * EnumValue constructor.
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @param mixed $value
     * @return ValueInterface
     */
    public static function parse($value): ValueInterface
    {
        try {
            $value = (string)$value;
        } catch (\Throwable $e) {
            throw new \InvalidArgumentException(\sprintf(self::ERROR_INVALID_TYPE, \gettype($value)));
        }

        if (\in_array($value, self::RESERVED_CHARACTERS, true)) {
            throw new \InvalidArgumentException(\sprintf(self::ERROR_RESERVED, $value));
        }

        if (((int)\preg_match('/^[_A-Za-z][_0-9A-Za-z]*$/um', $value)) === 0) {
            throw new \InvalidArgumentException(\sprintf(self::ERROR_INVALID_FORMAT, $value));
        }

        return new static($value);
    }

    /**
     * @return string
     */
    public function toPHPValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->value;
    }
}
