<?php

declare(strict_types=1);

namespace Railt\SDL\Node;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL
 */
final class IdentifierNode extends Node
{
    /**
     * @var non-empty-string
     */
    public string $value;

    /**
     * @param non-empty-string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }
}
