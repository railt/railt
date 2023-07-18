<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

abstract class Expression implements ExpressionInterface
{
    /**
     * @var non-empty-string|null
     */
    private ?string $hash = null;

    /**
     * @return non-empty-string
     *
     * @throws \Exception
     */
    public function getHash(): string
    {
        return $this->hash ??= \hash('xxh64', \random_bytes(64));
    }

    public function jsonSerialize(): array
    {
        /** @var array<non-empty-string, mixed> */
        return \get_object_vars($this);
    }
}
