<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Evaluate;

use Railt\SDL\Compiler\Command\CommandInterface;
use Railt\SDL\Compiler\Context;
use Railt\SDL\Exception\CompilationException;
use Railt\SDL\Node\NodeInterface;
use Railt\TypeSystem\Execution\Argument;
use Railt\TypeSystem\Execution\Directive;
use Railt\TypeSystem\ListType;
use Railt\TypeSystem\NonNullType;
use Railt\TypeSystem\TypeInterface;
use Railt\TypeSystem\WrappingTypeInterface;

final class CheckMissingDirectiveArgumentsCommand implements CommandInterface
{
    public function __construct(
        private readonly Context $ctx,
        private readonly NodeInterface $node,
        private readonly Directive $directive,
    ) {}

    public function exec(): void
    {
        $definition = $this->directive->getDefinition();

        foreach ($definition->getArguments() as $argument) {
            // In case of argument defined
            // Or default value is present
            if ($argument->hasDefaultValue() || $this->directive->hasArgument($argument->getName())) {
                continue;
            }

            // In case of argument is nullable - set "NULL" as default value.
            if ($this->ctx->config->castNullableTypeToDefaultValue
                && $this->isNullable($argument->getType())
            ) {
                $this->directive->addArgument(new Argument(
                    definition: $argument,
                    value: null,
                ));
                continue;
            }

            // In case of argument is list - set "[]" as default value.
            if ($this->ctx->config->castListTypeToDefaultValue
                && $this->isList($argument->getType())
            ) {
                $this->directive->addArgument(new Argument(
                    definition: $argument,
                    value: [],
                ));
                continue;
            }

            // Otherwise an exception will be thrown
            $message = \vsprintf('Missing required %s defined in %s', [
                (string)$argument,
                (string)$this->directive,
            ]);

            throw CompilationException::create($message, $this->node);
        }
    }

    private function isList(TypeInterface $type): bool
    {
        return $type instanceof WrappingTypeInterface && $type->is(ListType::class);
    }

    private function isNullable(TypeInterface $type): bool
    {
        return !$type instanceof WrappingTypeInterface || !$type->is(NonNullType::class);
    }
}
