<?php

declare(strict_types=1);

namespace Railt\SDL\Node\Statement\Extension;

use Railt\SDL\Node\Statement\Execution\DirectiveNode;
use Railt\SDL\Node\Statement\SchemaFieldNode;
use Railt\SDL\Node\Statement\Statement;
use Railt\SDL\Node\Visitable;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL
 */
final class SchemaExtensionNode extends Statement
{
    /**
     * @param list<SchemaFieldNode> $fields
     * @param list<DirectiveNode> $directives
     */
    public function __construct(
        #[Visitable]
        public array $fields = [],
        #[Visitable]
        public array $directives = [],
    ) {}
}
