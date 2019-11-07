<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast\Value;

/**
 * Class IntValueNode
 *
 * <code>
 *  export type IntValueNode = {
 *      +kind: 'IntValue',
 *      +loc?: Location,
 *      +value: string,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L319
 */
class IntValueNode extends ValueNode
{
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
    public int $value;

    /**
     * IntValueNode constructor.
     *
     * @param int $value
     * @throws \OverflowException
     */
    public function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * @param string $value
     * @return static
     * @throws \OverflowException
     */
    public static function parse(string $value): self
    {
        if (! \is_numeric($value)) {
            $message = 'Int cannot represent non-numeric value: %s';
            throw new \OverflowException(\sprintf($message, $value));
        }

        $integer = (int)$value;

        // As per the GraphQL Spec, Integers are only treated as valid when a valid
        // 32-bit signed integer, providing the broadest support across platforms.
        if ($integer >= self::MAX_INTEGER || $integer < self::MIN_INTEGER) {
            $message = 'Int cannot represent non 32-bit signed integer value: %s';
            throw new \OverflowException(\sprintf($message, $value));
        }

        return new static($integer);
    }

    /**
     * @return int
     */
    public function toNative(): int
    {
        return $this->value;
    }
}
