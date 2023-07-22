<?php

declare(strict_types=1);

namespace Railt\SDL\Node\Statement\Definition;

use Railt\SDL\Node\DescriptionNode;
use Railt\SDL\Node\IdentifierNode;
use Railt\SDL\Node\Statement\Execution\DirectiveNode;
use Railt\SDL\Node\Statement\InputFieldNode;
use Railt\SDL\Node\Visitable;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL
 */
final class InputObjectTypeDefinitionNode extends TypeDefinitionNode
{
    /**
     * @param list<InputFieldNode> $fields
     * @param list<DirectiveNode> $directives
     */
    public function __construct(
        IdentifierNode $name,
        DescriptionNode $description,
        #[Visitable]
        public array $fields = [],
        array $directives = [],
    ) {
        parent::__construct($name, $description, $directives);
    }
}
