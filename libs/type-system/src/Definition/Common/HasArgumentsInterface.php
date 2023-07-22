<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Definition\Common;

use Railt\TypeSystem\Definition\ArgumentDefinition;

interface HasArgumentsInterface
{
    /**
     * @param non-empty-string $name
     */
    public function getArgument(string $name): ?ArgumentDefinition;

    /**
     * @return int<0, max>
     */
    public function getNumberOfArguments(): int;

    /**
     * @param non-empty-string $name
     */
    public function hasArgument(string $name): bool;

    /**
     * @return iterable<ArgumentDefinition>
     */
    public function getArguments(): iterable;
}
