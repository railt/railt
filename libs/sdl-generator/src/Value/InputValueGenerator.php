<?php

declare(strict_types=1);

namespace Railt\SDL\Generator\Value;

use Railt\SDL\Generator\Config;
use Railt\SDL\Generator\Generator;

class InputValueGenerator extends Generator
{
    /**
     * @param iterable<non-empty-string, mixed> $values
     */
    public function __construct(
        public readonly iterable $values,
        Config $config = new Config(),
        public readonly bool $asInput = true,
    ) {
        parent::__construct($config);
    }

    public function isMultiline(): bool
    {
        return StringValueGenerator::isOneOfMultilineString($this->values);
    }

    /**
     * @psalm-suppress MixedAssignment
     */
    private function printAsMultiline(): string
    {
        $result = [];

        foreach ($this->values as $key => $value) {
            $result[] = $this->printer->prefixed(1, '%s: %s', [
                $key,
                $this->value($value),
            ]);
        }

        return \vsprintf($this->asInput ? '{%s%s%s}' : '%s%s%s', [
            $this->config->delimiter,
            \implode($this->config->delimiter, $result),
            $this->config->delimiter,
        ]);
    }

    /**
     * @psalm-suppress MixedAssignment
     */
    private function printAsInline(): string
    {
        $result = [];

        foreach ($this->values as $key => $value) {
            $result[] = \sprintf('%s: %s', $key, (string)$this->value($value));
        }

        if ($this->asInput) {
            return \sprintf('{%s}', \implode(', ', $result));
        }

        return \implode(', ', $result);
    }

    public function __toString(): string
    {
        if (StringValueGenerator::isOneOfMultilineString($this->values)) {
            return $this->printAsMultiline();
        }

        return $this->printAsInline();
    }
}
