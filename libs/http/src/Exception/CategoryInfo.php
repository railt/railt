<?php

declare(strict_types=1);

namespace Railt\Http\Exception;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\Http\Exception
 */
#[\Attribute(\Attribute::TARGET_CLASS_CONSTANT)]
final class CategoryInfo
{
    /**
     * @param non-empty-string|null $name
     */
    public function __construct(
        public readonly ?string $name = null,
        public readonly bool $isClientSafe = false,
    ) {}
}
