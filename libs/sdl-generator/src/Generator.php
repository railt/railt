<?php

declare(strict_types=1);

namespace Railt\SDL\Generator;

use Railt\SDL\Generator\Execution\DirectiveGenerator;
use Railt\SDL\Generator\Internal\Printer;
use Railt\SDL\Generator\Value\StringValueGenerator;
use Railt\SDL\Generator\Value\ValueGeneratorFactory;
use Railt\TypeSystem\Definition\NamedTypeDefinitionInterface;
use Railt\TypeSystem\Execution\Directive;
use Railt\TypeSystem\ListType;
use Railt\TypeSystem\NonNullType;
use Railt\TypeSystem\TypeInterface;

abstract class Generator implements GeneratorInterface
{
    protected readonly Printer $printer;

    public function __construct(
        protected readonly Config $config,
    ) {
        $this->printer = new Printer($this->config);
    }

    protected function value(mixed $value): \Stringable
    {
        return new ValueGeneratorFactory($value, $this->config);
    }

    /**
     * @param int<0, max> $indentation
     */
    protected function directive(Directive $directive, int $indentation = 0): string|\Stringable
    {
        $result = new DirectiveGenerator($directive, $this->config);

        if ($indentation !== 0) {
            return $this->printer->prefixed($indentation, (string)$result);
        }

        return $result;
    }

    /**
     * @param int<0, max> $indentation
     */
    protected function description(string $text, int $indentation = 0): string|\Stringable
    {
        $result = new StringValueGenerator($text, $this->config);

        if ($indentation !== 0) {
            return $this->printer->prefixed($indentation, (string)$result);
        }

        return $result;
    }

    protected function type(TypeInterface $type): string
    {
        if ($type instanceof ListType) {
            return \sprintf('[%s]', $this->type($type->getOfType()));
        }

        if ($type instanceof NonNullType) {
            return \sprintf('%s!', $this->type($type->getOfType()));
        }

        if ($type instanceof NamedTypeDefinitionInterface) {
            return $type->getName();
        }

        throw new \InvalidArgumentException(
            \sprintf('Could not print %s type', \get_debug_type($type))
        );
    }
}
