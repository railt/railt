<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

/**
 * @template-implements \ArrayAccess<non-empty-string, mixed>
 * @template-implements \IteratorAggregate<non-empty-string, mixed>
 */
final class InputObject extends Expression implements
    NameAwareInterface,
    \IteratorAggregate,
    \ArrayAccess,
    \Countable
{
    /**
     * @param InputObjectTypeDefinition $definition
     * @param array<non-empty-string, mixed> $values
     */
    public function __construct(
        private readonly InputObjectTypeDefinition $definition,
        private readonly array $values = [],
    ) {
    }

    /**
     * @param non-empty-string $offset
     *
     * @psalm-suppress RedundantConditionGivenDocblockType: Additional assertion
     * @psalm-suppress DocblockTypeContradiction : Additional assertion
     */
    public function offsetExists(mixed $offset): bool
    {
        assert(\is_string($offset), $this->typeError($offset));
        assert($offset !== '', $this->typeError($offset));

        if (\array_key_exists($offset, $this->values)) {
            return true;
        }

        $field = $this->definition->getField($offset);

        return $field?->hasDefaultValue() === true;
    }

    /**
     * @param non-empty-string $offset
     *
     * @psalm-suppress RedundantConditionGivenDocblockType: Additional assertion
     * @psalm-suppress DocblockTypeContradiction : Additional assertion
     */
    public function offsetGet(mixed $offset): mixed
    {
        assert(\is_string($offset), $this->typeError($offset));
        assert($offset !== '', $this->typeError($offset));

        if (\array_key_exists($offset, $this->values)) {
            return $this->values[$offset];
        }

        $field = $this->definition->getField($offset);

        return $field?->getDefaultValue();
    }

    /**
     * @internal object is immutable.
     *
     * @param non-empty-string $offset
     *
     * @psalm-suppress RedundantConditionGivenDocblockType: Additional assertion
     * @psalm-suppress DocblockTypeContradiction : Additional assertion
     *
     * @return never
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        assert(\is_string($offset), $this->typeError($offset));
        assert($offset !== '', $this->typeError($offset));

        throw new \BadMethodCallException(self::class . ' objects are immutable');
    }

    /**
     * @internal object is immutable.
     *
     * @param non-empty-string $offset
     *
     * @psalm-suppress RedundantConditionGivenDocblockType: Additional assertion
     * @psalm-suppress DocblockTypeContradiction : Additional assertion
     *
     * @return never
     */
    public function offsetUnset(mixed $offset): void
    {
        assert(\is_string($offset), $this->typeError($offset));
        assert($offset !== '', $this->typeError($offset));

        throw new \BadMethodCallException(self::class . ' objects are immutable');
    }

    private function typeError(string $offset): \TypeError
    {
        $trace = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS);

        $error = new \TypeError(\sprintf(
            'Argument #1 ($offset) must be of type non-empty-string, %s given',
            \get_debug_type($offset)
        ));

        $reflection = new \ReflectionObject($error);

        $file = $reflection->getProperty('file');
        $file->setValue($error, $trace[1]['file'] ?? __FILE__);

        $line = $reflection->getProperty('line');
        $line->setValue($error, $trace[1]['line'] ?? __LINE__);

        return $error;
    }

    /**
     * @return int<0, max>
     * @throws \Exception
     */
    public function count(): int
    {
        /** @var int<0, max> */
        return \iterator_count($this->getIterator());
    }

    /**
     * @return \Generator
     *
     * @psalm-return \Generator<string, mixed, mixed, void>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->values;

        foreach ($this->getFieldsWithDefaultValues() as $name => $field) {
            if (!\array_key_exists($name, $this->values)) {
                yield $name => $field->getDefaultValue();
            }
        }
    }

    /**
     * @return array<non-empty-string, InputFieldDefinition>
     */
    private function getFieldsWithDefaultValues(): array
    {
        $result = [];

        foreach ($this->definition->getFields() as $field) {
            if ($field->hasDefaultValue()) {
                $result[$field->getName()] = $field;
            }
        }

        return $result;
    }

    public function getName(): string
    {
        return $this->definition->getName();
    }

    public function getDefinition(): InputObjectTypeDefinition
    {
        return $this->definition;
    }

    /**
     * @return array<non-empty-string, mixed>
     * @throws \Exception
     */
    public function jsonSerialize(): array
    {
        $result = [];

        /** @var mixed $value */
        foreach ($this->getIterator() as $name => $value) {
            /** @psalm-suppress MixedAssignment */
            $result[$name] = $value;

            if ($value instanceof self && $value === $this) {
                $field = $this->definition->getField($name);
                $type = $field?->getType();

                if ($type instanceof NonNullType) {
                    $message = \vsprintf('Cannot serialize to JSON non-nullable %s of type %s', [
                        (string)$field,
                        (string)$type,
                    ]);

                    throw new \OutOfRangeException($message);
                }

                $result[$name] = null;
            }
        }

        return $result;
    }

    /**
     * @psalm-return array<string, mixed>
     */
    public function __debugInfo(): array
    {
        return $this->values;
    }

    public function __toString(): string
    {
        return \sprintf('input<%s>', $this->getName());
    }
}
