<?php

declare(strict_types=1);

namespace Railt\Contracts\Http\Response;

/**
 * @template TValue of mixed
 */
interface ExtensionInterface
{
    /**
     * @return non-empty-string
     */
    public function getName(): string;

    /**
     * @return TValue
     */
    public function getValue(): mixed;
}
