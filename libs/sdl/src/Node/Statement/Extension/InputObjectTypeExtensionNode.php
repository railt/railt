<?php

declare(strict_types=1);

namespace Railt\SDL\Node\Statement\Extension;

use Railt\SDL\Attribute\Visitable;
use Railt\SDL\Node\Expression\DirectiveNode;
use Railt\SDL\Node\IdentifierNode;
use Railt\SDL\Node\Statement\InputFieldNode;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL
 */
final class InputObjectTypeExtensionNode extends TypeExtensionNode
{
    /**
     * @param list<InputFieldNode> $fields
     * @param list<DirectiveNode> $directives
     */
    public function __construct(
        IdentifierNode $name,
        #[Visitable]
        public array $fields = [],
        array $directives = [],
    ) {
        parent::__construct($name, $directives);
    }
}
