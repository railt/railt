<?php

declare(strict_types=1);

namespace Railt\Contracts\Http;

/**
 * @template TValue of mixed
 */
interface OutputInterface
{
    /**
     * @return TValue
     */
    public function getValue(): mixed;

    /**
     * @return non-empty-string
     */
    public function getTypeName(): string;
}
