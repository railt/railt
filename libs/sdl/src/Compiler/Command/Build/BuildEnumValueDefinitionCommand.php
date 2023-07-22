<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Build;

use Railt\SDL\Compiler\Command\BuildChildCommand;
use Railt\SDL\Compiler\Command\Evaluate\EvaluateDirective;
use Railt\SDL\Exception\CompilationException;
use Railt\SDL\Node\Statement\EnumFieldNode;
use Railt\TypeSystem\Definition\EnumValueDefinition;
use Railt\TypeSystem\Definition\Type\EnumType;

/**
 * @template-extends BuildChildCommand<EnumFieldNode, EnumType>
 *
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL\Compiler\Command
 */
final class BuildEnumValueDefinitionCommand extends BuildChildCommand
{
    public function exec(): void
    {
        $this->assertFieldNotDefined();

        $value = EnumValueDefinition::fromName($this->node->name->value);

        if ($this->node->description->value !== null) {
            $value->setDescription($this->node->description->value->value);
        }

        foreach ($this->node->directives as $node) {
            $this->ctx->push(new EvaluateDirective(
                ctx: $this->ctx,
                node: $node,
                parent: $value,
            ));
        }

        $this->definition->addValue($value);
    }

    private function assertFieldNotDefined(): void
    {
        if ($this->definition->hasValue($this->node->name->value)) {
            $message = \vsprintf('Cannot redefine already defined enum value "%s" in %s', [
                $this->node->name->value,
                (string)$this->definition,
            ]);

            throw CompilationException::create($message, $this->node);
        }
    }
}
