<?php

declare(strict_types=1);

namespace Railt\SDL\Node\Statement\Definition;

use Railt\SDL\Node\DescriptionNode;
use Railt\SDL\Node\NameNode;
use Railt\SDL\Node\Statement\EnumFieldNode;
use Railt\SDL\Node\Statement\Execution\DirectiveNode;
use Railt\SDL\Node\Visitable;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL
 */
final class EnumTypeDefinitionNode extends TypeDefinitionNode
{
    /**
     * @param list<EnumFieldNode> $fields
     * @param list<DirectiveNode> $directives
     */
    public function __construct(
        NameNode $name,
        DescriptionNode $description,
        #[Visitable]
        public array $fields = [],
        array $directives = [],
    ) {
        parent::__construct($name, $description, $directives);
    }
}
