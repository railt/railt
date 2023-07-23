<?php

declare(strict_types=1);

namespace Railt\SDL\Exception;

use Railt\SDL\Node\NodeInterface;

class CompilationException extends RuntimeException
{
    public static function create(string $message, NodeInterface $node, int $code = 0): static
    {
        return new static(
            message: $message,
            source: $node->getSource(),
            position: $node->getPosition(),
            code: $code,
        );
    }
}
