<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Evaluate;

use Railt\TypeSystem\Definition\ArgumentDefinition;
use Railt\TypeSystem\Definition\FieldDefinition;
use Railt\TypeSystem\Definition\InputFieldDefinition;

/**
 * @template-extends ApplyDeprecationCommand<ArgumentDefinition|FieldDefinition|InputFieldDefinition>
 *
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL\Compiler\Command
 */
final class ApplyDeprecationFromTypeReferenceCommand extends ApplyDeprecationCommand
{
    public function __construct(
        ArgumentDefinition|FieldDefinition|InputFieldDefinition $context,
    ) {
        parent::__construct($context);
    }

    public function exec(): void
    {
        foreach ($this->getDeprecationDirectives($this->context->getType()) as $directive) {
            $this->deprecate($directive, $this->context);
        }
    }
}
