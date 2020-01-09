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
 * Class InputObjectValue
 */
final class InputObjectValue extends Value implements \IteratorAggregate, \ArrayAccess, \Countable
{
    /**
     * @var string
     */
    private const ERROR_INVALID_TYPE = 'GraphQL InputObject cannot represent a non-iterable %s';

    /**
     * @var string
     */
    private const ERROR_INVALID_CHILD = 'GraphQL InputObject #%s field must be a GraphQL value (instance of %s), but %s given';

    /**
     * @var string
     */
    private const ERROR_INVALID_KEY_TYPE = 'GraphQL InputObject\'s key cannot represent a non-string value of type %s';

    /**
     * @var string
     */
    private const ERROR_INVALID_KEY_FORMAT = '%s is an invalid key of GraphQL InputObject';


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

        $result = [];

        foreach ($value as $key => $child) {
            self::assertItem($key = self::key($key), $child);

            $result[$key] = $child;
        }

        return new static($result);
    }

    /**
     * @param $key
     * @return string
     */
    private static function key($key): string
    {
        try {
            $key = (string)$key;
        } catch (\Throwable $e) {
            throw new \InvalidArgumentException(\sprintf(self::ERROR_INVALID_KEY_TYPE, \gettype($key)));
        }

        if (((int)\preg_match('/^[_A-Za-z][_0-9A-Za-z]*$/um', $key)) === 0) {
            throw new \InvalidArgumentException(\sprintf(self::ERROR_INVALID_KEY_FORMAT, $key));
        }

        return $key;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    private static function assertItem(string $key, $value): void
    {
        if (! $value instanceof ValueInterface) {
            $given = \is_object($value) ? 'instance of ' . \get_class($value) : \gettype($value);

            $message = \sprintf(self::ERROR_INVALID_CHILD, $key, ValueInterface::class, $given);

            throw new \InvalidArgumentException($message);
        }
    }

    /**
     * @return array
     */
    public function toPHPValue(): array
    {
        $result = [];

        foreach ($this->value as $key => $value) {
            $result[$key] = $value->toPHPValue();
        }

        return $result;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        $pairs = [];

        foreach ($this->value as $key => $value) {
            $pairs[] = $key . ': ' . $value->toString();
        }

        return '{' . \implode(', ', $pairs) . '}';
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
     * @param string $field
     * @return bool
     */
    public function offsetExists($field): bool
    {
        \assert(\is_string($field));

        return isset($this->value[$field]);
    }

    /**
     * @param string $field
     * @return ValueInterface|null
     */
    public function offsetGet($field): ?ValueInterface
    {
        \assert(\is_string($field));

        return $this->value[$field] ?? null;
    }

    /**
     * @param string $field
     * @param ValueInterface $value
     * @return void
     */
    public function offsetSet($field, $value): void
    {
        \assert(\is_string($field));
        \assert($value instanceof ValueInterface);

        $this->value[$field] = $value;

        \ksort($this->value);
    }

    /**
     * @param string $field
     * @return void
     */
    public function offsetUnset($field): void
    {
        \assert(\is_string($field));

        unset($this->value[$field]);
    }
}
