<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Definition\Common;

use Railt\TypeSystem\Definition\InputFieldDefinition;

interface HasInputFieldsInterface
{
    /**
     * @param non-empty-string $name
     */
    public function getField(string $name): ?InputFieldDefinition;

    /**
     * @return int<0, max>
     */
    public function getNumberOfFields(): int;

    /**
     * @param non-empty-string $name
     */
    public function hasField(string $name): bool;

    /**
     * @return iterable<InputFieldDefinition>
     */
    public function getFields(): iterable;
}
