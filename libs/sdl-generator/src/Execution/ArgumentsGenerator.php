<?php

declare(strict_types=1);

namespace Railt\SDL\Generator\Execution;

use Railt\SDL\Generator\Config;
use Railt\SDL\Generator\Value\InputValueGenerator;
use Railt\TypeSystem\Execution\Argument;

final class ArgumentsGenerator extends InputValueGenerator
{
    /**
     * @param iterable<Argument> $arguments
     */
    public function __construct(
        iterable $arguments,
        Config $config = new Config()
    ) {
        $values = [];

        foreach ($arguments as $argument) {
            /** @psalm-suppress MixedAssignment */
            $values[$argument->getName()] = $argument->getValue();
        }

        parent::__construct($values, $config, false);
    }
}
