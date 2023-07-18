<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command;

use Railt\SDL\Compiler\Context;
use Railt\SDL\Node\Statement\Statement;
use Railt\TypeSystem\DefinitionInterface;

/**
 * @template TStatementNode of Statement
 *
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL\Compiler\Command
 */
abstract class DefineCommand extends Command implements DefineCommandInterface
{
    /**
     * @param TStatementNode $stmt
     */
    final public function __construct(
        Context $ctx,
        protected readonly Statement $stmt,
    ) {
        parent::__construct($ctx);
    }

    /**
     * @template TDefinition of DefinitionInterface
     *
     * @param class-string<BuildCommand<Statement, TDefinition>> $command
     * @param TDefinition $definition
     */
    protected function build(string $command, DefinitionInterface $definition): void
    {
        $this->ctx->push(new $command(
            $this->ctx,
            $this->stmt,
            $definition,
        ));
    }
}
