<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Execution;

use Railt\TypeSystem\Definition\InputFieldDefinition;
use Railt\TypeSystem\Definition\Type\InputObjectType;
use Railt\TypeSystem\NamedExecution;

/**
 * @template-implements \ArrayAccess<non-empty-string, mixed>
 * @template-implements \IteratorAggregate<non-empty-string, mixed>
 */
class InputObject extends NamedExecution implements
    ExpressionInterface,
    \IteratorAggregate,
    \ArrayAccess,
    \Countable
{
    /**
     * @param InputObjectType $definition
     * @param array<non-empty-string, mixed> $values
     */
    public function __construct(
        private readonly InputObjectType $definition,
        private readonly array $values = [],
    ) {}

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
     */
    public function offsetUnset(mixed $offset): void
    {
        assert(\is_string($offset), $this->typeError($offset));
        assert($offset !== '', $this->typeError($offset));

        throw new \BadMethodCallException(self::class . ' objects are immutable');
    }

    private function typeError(mixed $offset): \TypeError
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
    public function count(bool $includeDefaults = true): int
    {
        /** @var int<0, max> */
        return \iterator_count(
            $this->getIterator($includeDefaults),
        );
    }

    public function getIterator(bool $includeDefaults = false): \Traversable
    {
        yield from $this->values;

        if ($includeDefaults) {
            foreach ($this->getFieldsWithDefaultValues() as $name => $field) {
                if (!\array_key_exists($name, $this->values)) {
                    yield $name => $field->getDefaultValue();
                }
            }
        }
    }

    /**
     * @return array<non-empty-string, mixed>
     *
     * @throws \Exception
     */
    public function toArray(bool $includeDefaults = false): array
    {
        return \iterator_to_array(
            $this->getIterator($includeDefaults),
        );
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

    public function getDefinition(): InputObjectType
    {
        return $this->definition;
    }

    /**
     * @return array<non-empty-string, mixed>
     * @throws \Exception
     */
    public function jsonSerialize(): array
    {
        return $this->toArray(false);
    }

    /**
     * @param non-empty-string $name
     */
    public function __get(string $name): mixed
    {
        return $this->offsetGet($name);
    }

    /**
     * @param non-empty-string $name
     */
    public function __set(string $name, mixed $value): void
    {
        $this->offsetSet($name, $value);
    }

    /**
     * @param non-empty-string $name
     */
    public function __isset(string $name): bool
    {
        return $this->offsetExists($name);
    }

    /**
     * @param non-empty-string $name
     */
    public function __unset(string $name): void
    {
        $this->offsetUnset($name);
    }

    public function __debugInfo(): array
    {
        return $this->values;
    }

    public function __toString(): string
    {
        return (string)$this->definition;
    }
}
