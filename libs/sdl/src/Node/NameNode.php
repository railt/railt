<?php

declare(strict_types=1);

namespace Railt\SDL\Node;

final class NameNode extends Node
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
