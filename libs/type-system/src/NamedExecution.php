<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

abstract class NamedExecution extends Execution implements NamedExecutionInterface
{
    /**
     * @var non-empty-string|null
     */
    protected ?string $hash = null;

    /**
     * @return non-empty-string
     *
     * @throws \Exception
     */
    public function getHash(): string
    {
        return $this->hash ??= \hash('xxh3', (string)$this);
    }
}
