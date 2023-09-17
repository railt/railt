<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Evaluate;

use Railt\SDL\Compiler\Command\CommandInterface;
use Railt\SDL\Compiler\Context;
use Railt\SDL\Node\Statement\InputFieldNode;
use Railt\TypeSystem\Definition\InputFieldDefinition;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL\Compiler\Command
 */
final class EvaluateInputFieldDefaultValue implements CommandInterface
{
    public function __construct(
        private readonly Context $ctx,
        private readonly InputFieldDefinition $field,
        private readonly InputFieldNode $node,
    ) {}

    public function exec(): void
    {
        if ($this->node->default === null) {
            return;
        }

        /** @psalm-suppress MixedAssignment : Okay */
        $value = $this->ctx->eval($this->field->getType(), $this->node->default);

        $this->field->setDefaultValue($value);
    }
}
