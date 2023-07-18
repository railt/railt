<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Evaluate;

use Railt\SDL\Compiler\Command\CommandInterface;
use Railt\SDL\Compiler\Context;
use Railt\SDL\Exception\CompilationException;
use Railt\SDL\Node\Expression\DirectiveNode;
use Railt\TypeSystem\Definition;
use Railt\TypeSystem\Directive;
use Railt\TypeSystem\DirectiveDefinition;
use Railt\TypeSystem\DirectivesProviderInterface;

final class EvaluateDirective implements CommandInterface
{
    public function __construct(
        private readonly Context $ctx,
        private readonly DirectiveNode $node,
        private readonly Definition&DirectivesProviderInterface $parent,
    ) {}

    public function exec(): void
    {
        $definition = $this->ctx->getDirective(
            name: $this->node->name->value,
            node: $this->node->name,
            from: $this->parent,
        );

        $this->assertValidLocation($definition);
        $this->assertRepetition($definition);

        $directive = new Directive($definition);

        foreach ($this->node->arguments as $node) {
            $this->ctx->push(new EvaluateArgumentCommand(
                ctx: $this->ctx,
                node: $node,
                directive: $directive,
            ));
        }

        $this->parent->addDirective($directive);
    }

    private function assertRepetition(DirectiveDefinition $directive): void
    {
        if (!$directive->isRepeatable() && $this->parent->hasDirective($directive->getName())) {
            $message = \vsprintf('Directive "@%s" is not repeatable and cannot be applied twice', [
                $directive->getName(),
            ]);

            throw CompilationException::create($message, $this->node->name);
        }
    }

    private function assertValidLocation(DirectiveDefinition $directive): void
    {
        // Check directive location
        if (!$directive->isAvailableFor($this->parent)) {
            $message = \vsprintf('Directive "@%s" cannot be applied on %s', [
                $directive->getName(),
                (string)$this->parent,
            ]);

            throw CompilationException::create($message, $this->node);
        }
    }
}
