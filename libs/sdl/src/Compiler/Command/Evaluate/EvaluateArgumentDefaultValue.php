<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Evaluate;

use Railt\SDL\Compiler\Command\CommandInterface;
use Railt\SDL\Compiler\Context;
use Railt\SDL\Node\Statement\ArgumentNode;
use Railt\TypeSystem\ArgumentDefinition;

final readonly class EvaluateArgumentDefaultValue implements CommandInterface
{
    public function __construct(
        private Context $ctx,
        private ArgumentDefinition $argument,
        private ArgumentNode $node,
    ) {}

    public function exec(): void
    {
        if ($this->node->default === null) {
            return;
        }

        $value = $this->ctx->eval($this->argument->getType(), $this->node->default);

        $this->argument->setDefaultValue($value);
    }
}
