<?php

declare(strict_types=1);

namespace Railt\SDL\Generator\Value;

use Railt\SDL\Generator\Config;
use Railt\SDL\Generator\Generator;
use Railt\TypeSystem\Execution\EnumValue;
use Railt\TypeSystem\Execution\InputObject;

final class ValueGeneratorFactory extends Generator
{
    public function __construct(
        public readonly mixed $value,
        Config $config = new Config(),
    ) {
        parent::__construct($config);
    }

    /**
     * @psalm-suppress MixedArgumentTypeCoercion
     */
    public function __toString(): string
    {
        return (string)match (true) {
            \is_null($this->value) => 'null',
            \is_bool($this->value) => $this->value ? 'true' : 'false',
            \is_string($this->value)
                => new StringValueGenerator($this->value, $this->config),
            \is_int($this->value),
            \is_float($this->value) => (string)$this->value,
            $this->value instanceof InputObject
                => new InputValueGenerator($this->value, $this->config),
            $this->value instanceof EnumValue
                => $this->value->getName(),
            \is_iterable($this->value) => \is_array($this->value) && \array_is_list($this->value)
                ? new ListValueGenerator($this->value, $this->config)
                : new InputValueGenerator($this->value, $this->config),
            default => throw new \InvalidArgumentException(
                \sprintf('Could not print %s value', \get_debug_type($this->value))
            ),
        };
    }
}
