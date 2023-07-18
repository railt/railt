<?php

declare(strict_types=1);

namespace Railt\SDL\Node\Statement\Extension;

use Railt\SDL\Attribute\Visitable;
use Railt\SDL\Node\Expression\DirectiveNode;
use Railt\SDL\Node\IdentifierNode;
use Railt\SDL\Node\Statement\Statement;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL
 */
abstract class TypeExtensionNode extends Statement
{
    /**
     * @param list<DirectiveNode> $directives
     */
    public function __construct(
        #[Visitable]
        public IdentifierNode $name,
        #[Visitable]
        public array $directives = [],
    ) {}
}