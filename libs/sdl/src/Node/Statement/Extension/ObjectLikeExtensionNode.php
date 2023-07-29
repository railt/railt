<?php

declare(strict_types=1);

namespace Railt\SDL\Node\Statement\Extension;

use Railt\SDL\Node\NameNode;
use Railt\SDL\Node\Statement\Execution\DirectiveNode;
use Railt\SDL\Node\Statement\FieldNode;
use Railt\SDL\Node\Statement\Type\NamedTypeNode;
use Railt\SDL\Node\Visitable;

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
        NameNode $name,
        #[Visitable]
        public array $interfaces = [],
        #[Visitable]
        public array $fields = [],
        array $directives = [],
    ) {
        parent::__construct($name, $directives);
    }
}
