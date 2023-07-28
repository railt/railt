<?php

declare(strict_types=1);

namespace Railt\Contracts\Http\Input;

interface ArgumentsProviderInterface
{
    /**
     * @param non-empty-string $name
     */
    public function hasArgument(string $name): bool;

    /**
     * @param non-empty-string $name
     */
    public function getArgument(string $name, mixed $default = null): mixed;

    /**
     * @return iterable<non-empty-string, mixed>
     */
    public function getArguments(): iterable;
}
