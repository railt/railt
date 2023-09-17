<?php

declare(strict_types=1);

namespace Railt\SDL\Node\Statement\Execution;

use Railt\SDL\Node\IdentifierNode;
use Railt\SDL\Node\Visitable;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL
 */
final class DirectiveNode extends Execution
{
    /**
     * @param list<ArgumentNode> $arguments
     */
    public function __construct(
        #[Visitable]
        public IdentifierNode $name,
        #[Visitable]
        public array $arguments = [],
    ) {}
}
