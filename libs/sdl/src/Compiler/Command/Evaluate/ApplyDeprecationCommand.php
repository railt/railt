<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Evaluate;

use Railt\SDL\Compiler\Command\CommandInterface;
use Railt\TypeSystem\Definition;
use Railt\TypeSystem\DeprecationAwareInterface;
use Railt\TypeSystem\DirectivesProviderInterface;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL\Compiler\Command
 */
final class ApplyDeprecationCommand implements CommandInterface
{
    public function __construct(
        private readonly Definition $context,
    ) {}

    public function exec(): void
    {
        // Skip definitions without directives
        if (!$this->context instanceof DirectivesProviderInterface) {
            return;
        }

        // Skip definitions without deprecations
        if (!$this->context instanceof DeprecationAwareInterface) {
            return;
        }

        foreach ($this->context->getDirectives('deprecated') as $directive) {
            $reason = $directive->getArgument('reason');

            $this->context->setDeprecationReason((string)$reason?->getValue());
        }
    }
}
