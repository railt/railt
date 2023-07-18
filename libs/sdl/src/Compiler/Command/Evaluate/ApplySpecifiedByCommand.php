<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Evaluate;

use Railt\SDL\Compiler\Command\CommandInterface;
use Railt\TypeSystem\ScalarTypeDefinition;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL\Compiler\Command
 */
final class ApplySpecifiedByCommand implements CommandInterface
{
    public function __construct(
        private readonly ScalarTypeDefinition $scalar,
    ) {}

    public function exec(): void
    {
        foreach ($this->scalar->getDirectives('specifiedBy') as $directive) {
            if (($url = $directive->getArgument('url')) !== null) {
                $this->scalar->setSpecificationUrl((string)$url->getValue());
            }
        }
    }
}
