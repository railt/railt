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
 * Class ListValue
 */
final class ListValue extends Value implements \IteratorAggregate, \ArrayAccess, \Countable
{
    /**
     * @var string
     */
    private const ERROR_INVALID_TYPE = 'GraphQL List cannot represent a non-iterable %s';

    /**
     * @var string
     */
    private const ERROR_INVALID_CHILD = 'GraphQL List #%d item must be a GraphQL value (instance of %s), but %s given';

    /**
     * @var array
     */
    private array $value;

    /**
     * ListValue constructor.
     *
     * @param array|ValueInterface[] $value
     */
    public function __construct(array $value)
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
        if (! \is_iterable($value)) {
            $type = \is_object($value) ? 'object of ' . \get_class($value) : 'value of type ' . \gettype($value);

            throw new \InvalidArgumentException(\sprintf(self::ERROR_INVALID_TYPE, $type));
        }

        [$result, $index] = [[], 0];

        foreach ($value as $child) {
            // Using "$index++" instruction avoids invalid iterator ($value) indices.
            self::assertItem($index++, $child);

            $result[] = $child;
        }

        return new static($result);
    }

    /**
     * @param int $index
     * @param mixed $value
     * @return void
     */
    private static function assertItem(int $index, $value): void
    {
        if (! $value instanceof ValueInterface) {
            $given = \is_object($value) ? 'instance of ' . \get_class($value) : \gettype($value);

            $message = \sprintf(self::ERROR_INVALID_CHILD, $index, ValueInterface::class, $given);

            throw new \InvalidArgumentException($message);
        }
    }

    /**
     * @return array
     */
    public function toPHPValue(): array
    {
        return \array_map(fn(ValueInterface $value) => $value->toPHPValue(), $this->value);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return \implode(', ', \array_map(fn (ValueInterface $value): string => $value->toString(), $this->value));
    }

    /**
     * @return \Traversable|ValueInterface[]
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->value);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->value);
    }

    /**
     * @param int $index
     * @return bool
     */
    public function offsetExists($index): bool
    {
        \assert(\is_int($index));

        return isset($this->value[$index]);
    }

    /**
     * @param int $index
     * @return ValueInterface|null
     */
    public function offsetGet($index): ?ValueInterface
    {
        \assert(\is_int($index));

        return $this->value[$index] ?? null;
    }

    /**
     * @param int $index
     * @param ValueInterface $value
     * @return void
     */
    public function offsetSet($index, $value): void
    {
        \assert(\is_int($index));
        \assert($value instanceof ValueInterface);

        $this->value[$index] = $value;

        \ksort($this->value);
    }

    /**
     * @param int $index
     * @return void
     */
    public function offsetUnset($index): void
    {
        \assert(\is_int($index));

        unset($this->value[$index]);
    }
}
