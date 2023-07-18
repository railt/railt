<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\TypeSystem
 */
#[\Attribute(\Attribute::TARGET_CLASS_CONSTANT)]
final readonly class DirectiveLocationInformation
{
    /**
     * @param class-string|null $ref
     */
    public function __construct(
        public ?string $ref = null,
        public bool $isExecutable = false,
    ) {
    }
}
