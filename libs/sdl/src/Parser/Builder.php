<?php

declare(strict_types=1);

namespace Railt\SDL\Parser;

use Phplrt\Parser\BuilderInterface;
use Phplrt\Parser\ContextInterface;
use Railt\SDL\Node\Node;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL
 */
final class Builder implements BuilderInterface
{
    /**
     * @param array<int<0, max>|non-empty-string, callable(ContextInterface, mixed):mixed> $reducers
     */
    public function __construct(
        private readonly array $reducers,
    ) {}

    public function build(ContextInterface $context, mixed $result): mixed
    {
        $state = $context->getState();

        if (isset($this->reducers[$state])) {
            /** @var mixed $result */
            $result = ($this->reducers[$state])($context, $result);

            if ($result instanceof Node) {
                $token = $context->getToken();
                $result->setContext($context->getSource(), $token->getOffset());
            }
        }

        return $result;
    }
}
