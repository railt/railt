<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Evaluate;

use Railt\SDL\Compiler\Command\CommandInterface;
use Railt\SDL\Exception\CompilationException;
use Railt\SDL\Node\NodeInterface;
use Railt\TypeSystem\InputObjectTypeDefinition;
use Railt\TypeSystem\NonNullType;
use Railt\TypeSystem\TypeInterface;
use Railt\TypeSystem\WrappingTypeInterface;

final class EvaluateInputObjectValue implements CommandInterface
{
    /**
     * @param array<non-empty-string, mixed> $defaults
     */
    public function __construct(
        private readonly NodeInterface $node,
        private readonly InputObjectTypeDefinition $input,
        private array &$defaults,
    ) {}

    public function exec(): void
    {
        foreach ($this->input->getFields() as $field) {
            // In case of field already defined
            // Or default value is defined
            if (\array_key_exists($field->getName(), $this->defaults)
                || $field->hasDefaultValue()
            ) {
                continue;
            }

            // In case of field is nullable - allow NULL as default value.
            if ($this->isNullable($field->getType())) {
                $this->defaults[$field->getName()] = null;
                continue;
            }

            // Otherwise an exception will be thrown
            $message = \vsprintf('Missing required input field "%s" of %s', [
                $field->getName(),
                (string)$this->input,
            ]);

            throw CompilationException::create($message, $this->node);
        }
    }

    private function isNullable(TypeInterface $type): bool
    {
        if ($type instanceof NonNullType) {
            return false;
        }

        if ($type instanceof WrappingTypeInterface) {
            return $this->isNullable($type->getOfType());
        }

        return true;
    }
}
