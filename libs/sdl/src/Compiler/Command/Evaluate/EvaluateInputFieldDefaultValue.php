<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Evaluate;

use Railt\SDL\Compiler\Command\CommandInterface;
use Railt\SDL\Compiler\Context;
use Railt\SDL\Node\Statement\InputFieldNode;
use Railt\TypeSystem\InputFieldDefinition;

final readonly class EvaluateInputFieldDefaultValue implements CommandInterface
{
    public function __construct(
        private Context $ctx,
        private InputFieldDefinition $field,
        private InputFieldNode $node,
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
