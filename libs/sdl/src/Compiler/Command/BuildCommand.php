<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command;

use Railt\SDL\Compiler\Command\Evaluate\EvaluateDirective;
use Railt\SDL\Compiler\Context;
use Railt\SDL\Node\Expression\DirectiveNode;
use Railt\SDL\Node\Statement\Statement;
use Railt\TypeSystem\Definition;
use Railt\TypeSystem\DefinitionInterface;
use Railt\TypeSystem\DirectivesProviderInterface;

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

    /**
     * @template TChildStatement of Statement
     *
     * @param class-string<BuildCommand<TChildStatement, DefinitionInterface>> $command
     * @param TChildStatement $stmt
     */
    protected function build(string $command, Statement $stmt): void
    {
        $this->ctx->push(new $command(
            ctx: $this->ctx,
            node: $stmt,
            definition: $this->definition,
        ));
    }

    protected function addDirective(Definition&DirectivesProviderInterface $type, DirectiveNode $node): void
    {
        $this->ctx->push(new EvaluateDirective(
            ctx: $this->ctx,
            node: $node,
            parent: $type,
        ));
    }
}
