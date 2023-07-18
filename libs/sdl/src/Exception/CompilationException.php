<?php

declare(strict_types=1);

namespace Railt\SDL\Exception;

use Railt\SDL\Node\NodeInterface;

class CompilationException extends RuntimeException
{
    public static function create(string $message, NodeInterface $node): static
    {
        return new static($message, $node->getSource(), $node->getPosition());
    }
}
