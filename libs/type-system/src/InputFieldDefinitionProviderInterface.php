<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

interface InputFieldDefinitionProviderInterface
{
    /**
     * @param non-empty-string $name
     */
    public function getField(string $name): ?InputFieldDefinition;

    /**
     * @return iterable<InputFieldDefinition>
     */
    public function getFields(): iterable;
}
