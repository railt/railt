<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command;

use Railt\SDL\Compiler\Context;
use Railt\SDL\Node\Statement\Statement;
use Railt\TypeSystem\DefinitionInterface;

/**
 * @template TStatementNode of Statement
 * @template TDefinition of DefinitionInterface
 */
abstract class BuildCommand extends Command implements BuildCommandInterface
{
    /**
     * @param TStatementNode $node
     * @param TDefinition $definition
     */
    final public function __construct(
        Context $ctx,
        protected readonly Statement $node,
        protected readonly DefinitionInterface $definition,
    ) {
        parent::__construct($ctx);
    }
}
