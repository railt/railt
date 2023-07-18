<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Evaluate;

use Railt\SDL\Compiler\Command\CommandInterface;
use Railt\TypeSystem\Definition;
use Railt\TypeSystem\Directive;
use Railt\TypeSystem\DirectivesProviderInterface;
use Railt\TypeSystem\TypeInterface;
use Railt\TypeSystem\WrappingTypeInterface;

/**
 * @template TContext of Definition
 *
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL\Compiler\Command
 */
class ApplyDeprecationCommand implements CommandInterface
{
    /**
     * @param TContext $context
     */
    public function __construct(
        protected readonly Definition $context,
    ) {}

    public function exec(): void
    {
        foreach ($this->getDeprecationDirectives($this->context) as $directive) {
            $this->deprecate($directive, $this->context);
        }
    }

    /**
     * @return iterable<Directive>
     */
    protected function getDeprecationDirectives(TypeInterface $type): iterable
    {
        if ($type instanceof WrappingTypeInterface) {
            return $this->getDeprecationDirectives($type->getOfType());
        }

        if ($type instanceof DirectivesProviderInterface) {
            return $type->getDirectives('deprecated');
        }

        return [];
    }

    private function getDeprecationReason(Directive $directive): ?string
    {
        if (($reason = $directive->getArgument('reason')) !== null) {
            return (string)$reason->getValue();
        }

        return $directive->getDefinition()
            ->getArgument('reason')
            ?->getDefaultValue()
        ;
    }

    protected function deprecate(Directive $directive, Definition $ctx): void
    {
        $reason = $this->getDeprecationReason($directive);

        if ($reason !== null && \method_exists($ctx, 'setDeprecationReason')) {
            $ctx->setDeprecationReason($reason);
        }
    }
}
