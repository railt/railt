<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Evaluate;

use Railt\SDL\Compiler\Command\CommandInterface;
use Railt\SDL\Config;
use Railt\SDL\Exception\CompilationException;
use Railt\SDL\Node\NodeInterface;
use Railt\TypeSystem\Definition\Type\InputObjectType;
use Railt\TypeSystem\ListType;
use Railt\TypeSystem\NonNullType;
use Railt\TypeSystem\TypeInterface;
use Railt\TypeSystem\WrappingTypeInterface;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL\Compiler
 */
final class EvaluateInputObjectValue implements CommandInterface
{
    /**
     * @param array<non-empty-string, mixed> $defaults
     */
    public function __construct(
        private readonly Config $config,
        private readonly NodeInterface $node,
        private readonly InputObjectType $input,
        private array &$defaults,
    ) {}

    public function exec(): void
    {
        foreach ($this->input->getFields() as $field) {
            // In case of field already defined
            // Or default value is defined
            if ($field->hasDefaultValue() || \array_key_exists($field->getName(), $this->defaults)) {
                continue;
            }

            // In case of input field is nullable - set "NULL" as default value.
            if ($this->config->castNullableTypeToDefaultValue
                && $this->isNullable($field->getType())
            ) {
                $this->defaults[$field->getName()] = null;
                continue;
            }

            // In case of input field is list - set "[]" as default value.
            if ($this->config->castListTypeToDefaultValue
                && $this->isList($field->getType())
            ) {
                $this->defaults[$field->getName()] = [];
                continue;
            }

            // Otherwise an exception will be thrown
            $message = \vsprintf('Missing required %s defined in %s', [
                (string)$field,
                (string)$this->input,
            ]);

            throw CompilationException::create($message, $this->node);
        }
    }

    private function isList(TypeInterface $type): bool
    {
        return $type instanceof WrappingTypeInterface && $type->is(ListType::class);
    }

    private function isNullable(TypeInterface $type): bool
    {
        return !$type instanceof WrappingTypeInterface || !$type->is(NonNullType::class);
    }
}
