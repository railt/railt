<?php

declare(strict_types=1);

namespace Railt\SDL\Generator\Value;

use Railt\SDL\Generator\Config;
use Railt\SDL\Generator\Generator;
use Railt\SDL\Generator\Internal\Printer;

final class ListValueGenerator extends Generator
{
    /**
     * @param list<mixed> $values
     */
    public function __construct(
        public readonly array $values,
        Config $config = new Config(),
    ) {
        parent::__construct($config);
    }

    private function printAsMultiline(): string
    {
        $result = [];

        /** @psalm-suppress MixedAssignment */
        foreach ($this->values as $value) {
            $result[] = $this->printer->prefixed(1, (string)$this->value($value));
        }

        return \vsprintf('[%s%s%s]', [
            $this->config->delimiter,
            \implode($this->config->delimiter, $result),
            $this->config->delimiter,
        ]);
    }

    private function printAsInline(): string
    {
        $result = [];

        /** @psalm-suppress MixedAssignment */
        foreach ($this->values as $value) {
            $result[] = $this->value($value);
        }

        return \sprintf('[%s]', \implode(', ', $result));
    }

    public function __toString(): string
    {
        if (StringValueGenerator::isOneOfMultilineString($this->values)) {
            return $this->printAsMultiline();
        }

        return $this->printAsInline();
    }
}
