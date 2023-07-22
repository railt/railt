<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Definition\Common;

use Railt\TypeSystem\Definition\FieldDefinition;

interface HasFieldsInterface
{
    /**
     * @param non-empty-string $name
     */
    public function getField(string $name): ?FieldDefinition;

    /**
     * @return int<0, max>
     */
    public function getNumberOfFields(): int;

    /**
     * @param non-empty-string $name
     */
    public function hasField(string $name): bool;

    /**
     * @return iterable<FieldDefinition>
     */
    public function getFields(): iterable;
}
