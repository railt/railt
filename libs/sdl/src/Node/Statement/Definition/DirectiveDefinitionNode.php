<?php

declare(strict_types=1);

namespace Railt\SDL\Node\Statement\Definition;

use Railt\SDL\Node\DescriptionNode;
use Railt\SDL\Node\IdentifierNode;
use Railt\SDL\Node\NameNode;
use Railt\SDL\Node\Statement\ArgumentNode;
use Railt\SDL\Node\Statement\Definition\DirectiveDefinition\Modifier;
use Railt\SDL\Node\Statement\Statement;
use Railt\SDL\Node\Visitable;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL
 */
final class DirectiveDefinitionNode extends Statement
{
    /**
     * @param list<ArgumentNode> $arguments
     * @param list<Modifier> $modifiers
     * @param list<IdentifierNode> $locations
     */
    public function __construct(
        #[Visitable]
        public NameNode $name,
        #[Visitable]
        public DescriptionNode $description,
        #[Visitable]
        public array $arguments = [],
        public array $modifiers = [],
        #[Visitable]
        public array $locations = [],
    ) {}

    public function isRepeatable(): bool
    {
        return \in_array(Modifier::REPEATABLE, $this->modifiers, true);
    }
}
