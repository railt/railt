<?php

declare(strict_types=1);

namespace Railt\SDL\Generator\Execution;

use Railt\SDL\Generator\Config;
use Railt\SDL\Generator\Generator;
use Railt\TypeSystem\Execution\Directive;

final class DirectiveGenerator extends Generator
{
    public function __construct(
        private readonly Directive $directive,
        Config $config = new Config(),
    ) {
        parent::__construct($config);
    }

    public function __toString(): string
    {
        if ($this->directive->getNumberOfArguments() === 0) {
            return \sprintf('@%s', $this->directive->getName());
        }

        $arguments = new ArgumentsGenerator(
            arguments: $this->directive->getArguments(),
            config: $this->config,
        );

        return \vsprintf('@%s(%s)', [
            $this->directive->getName(),
            (string)$arguments,
        ]);
    }
}
