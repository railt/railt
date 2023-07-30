<?php

declare(strict_types=1);

namespace Railt\SDL\Node\Statement\Definition;

use Railt\SDL\Node\DescriptionNode;
use Railt\SDL\Node\NameNode;
use Railt\SDL\Node\Statement\Execution\DirectiveNode;
use Railt\SDL\Node\Statement\Statement;
use Railt\SDL\Node\Visitable;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL
 */
abstract class TypeDefinitionNode extends Statement
{
    /**
     * @param list<DirectiveNode> $directives
     */
    public function __construct(
        #[Visitable]
        public NameNode $name,
        #[Visitable]
        public DescriptionNode $description,
        #[Visitable]
        public array $directives = [],
    ) {
    }
}
