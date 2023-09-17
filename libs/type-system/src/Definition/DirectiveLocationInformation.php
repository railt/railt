<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Definition;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\TypeSystem\Definition
 */
#[\Attribute(\Attribute::TARGET_CLASS_CONSTANT)]
final class DirectiveLocationInformation
{
    /**
     * @param class-string|null $ref
     */
    public function __construct(
        public readonly ?string $ref = null,
        public readonly bool $isExecutable = false,
    ) {}
}
