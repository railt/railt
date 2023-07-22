<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Definition\Common;

use Railt\TypeSystem\Definition\EnumValueDefinition;

interface HasEnumValuesInterface
{
    /**
     * @param non-empty-string $name
     */
    public function getValue(string $name): ?EnumValueDefinition;

    /**
     * @return int<0, max>
     */
    public function getNumberOfValues(): int;

    /**
     * @param non-empty-string $name
     */
    public function hasValue(string $name): bool;

    /**
     * @return iterable<EnumValueDefinition>
     */
    public function getValues(): iterable;
}
