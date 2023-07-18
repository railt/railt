<?php

declare(strict_types=1);

namespace Railt\SDL\Node\Statement;

use Railt\SDL\Attribute\Visitable;
use Railt\SDL\Node\DescriptionNode;
use Railt\SDL\Node\Expression\DirectiveNode;
use Railt\SDL\Node\Expression\Literal\LiteralNode;
use Railt\SDL\Node\IdentifierNode;
use Railt\SDL\Node\Statement\Type\TypeNode;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL
 */
final class InputFieldNode extends Statement
{
    /**
     * @param list<DirectiveNode> $directives
     */
    public function __construct(
        #[Visitable]
        public IdentifierNode $name,
        #[Visitable]
        public DescriptionNode $description,
        #[Visitable]
        public TypeNode $type,
        #[Visitable]
        public ?LiteralNode $default = null,
        #[Visitable]
        public array $directives = [],
    ) {}
}
