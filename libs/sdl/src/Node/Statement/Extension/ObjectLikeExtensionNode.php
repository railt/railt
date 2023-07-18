<?php

declare(strict_types=1);

namespace Railt\SDL\Node\Statement\Extension;

use Railt\SDL\Attribute\Visitable;
use Railt\SDL\Node\Expression\DirectiveNode;
use Railt\SDL\Node\IdentifierNode;
use Railt\SDL\Node\Statement\FieldNode;
use Railt\SDL\Node\Statement\Type\NamedTypeNode;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL
 */
abstract class ObjectLikeExtensionNode extends TypeExtensionNode
{
    /**
     * @param list<NamedTypeNode> $interfaces
     * @param list<FieldNode> $fields
     * @param list<DirectiveNode> $directives
     */
    public function __construct(
        IdentifierNode $name,
        #[Visitable]
        public array $interfaces = [],
        #[Visitable]
        public array $fields = [],
        array $directives = [],
    ) {
        parent::__construct($name, $directives);
    }
}
