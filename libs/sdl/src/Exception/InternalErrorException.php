<?php

declare(strict_types=1);

namespace Railt\SDL\Exception;

use Railt\SDL\Node\Node;

class InternalErrorException extends RuntimeException
{
    final public const CODE_UNKNOWN_ERROR = 0x01 + parent::CODE_LAST;

    protected const CODE_LAST = self::CODE_UNKNOWN_ERROR;

    public static function fromUnprocessableNode(Node $node): self
    {
        $message = \sprintf('Unprocessable AST Statement: %s', $node::class);

        return self::createFromNode($message, $node);
    }

    public static function createFromNode(string $message, Node $node): self
    {
        return new static(
            $message,
            $node->getSource(),
            $node->getPosition(),
            self::CODE_UNKNOWN_ERROR,
        );
    }
}
