<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Evaluate;

use Railt\SDL\Compiler\Command\CommandInterface;
use Railt\SDL\Compiler\Context;
use Railt\SDL\Exception\CompilationException;
use Railt\SDL\Node\Statement\Execution\ArgumentNode;
use Railt\TypeSystem\Execution\Argument;
use Railt\TypeSystem\Execution\Directive;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL\Compiler\Command
 */
final class EvaluateArgumentCommand implements CommandInterface
{
    public function __construct(
        private readonly Context $ctx,
        private readonly ArgumentNode $node,
        private readonly Directive $directive,
    ) {}

    public function exec(): void
    {
        $this->assertArgumentNotDefined();

        $directive = $this->directive->getDefinition();
        $argument = $directive->getArgument($this->node->name->value);

        if ($argument === null) {
            $message = \vsprintf('Argument "%s" not defined in %s', [
                $this->node->name->value,
                (string)$this->directive,
            ]);

            throw CompilationException::create($message, $this->node->name);
        }

        /** @psalm-suppress MixedAssignment : Okay */
        $value = $this->ctx->eval($argument->getType(), $this->node->value);

        $this->directive->addArgument(new Argument($argument, $value));
    }

    private function assertArgumentNotDefined(): void
    {
        if ($this->directive->hasArgument($this->node->name->value)) {
            $message = \vsprintf('Cannot redefine already defined argument "%s" in %s', [
                $this->node->name->value,
                (string)$this->directive,
            ]);

            throw CompilationException::create($message, $this->node->name);
        }
    }
}
