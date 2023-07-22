<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

abstract class NamedExecution extends Execution implements NamedExecutionInterface
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
}
