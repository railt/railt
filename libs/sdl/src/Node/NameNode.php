<?php

declare(strict_types=1);

namespace Railt\SDL\Node;

final class NameNode extends Node
{
    /**
     * @param non-empty-string $value
     */
    public function __construct(
        public string $value,
    ) {}
}
