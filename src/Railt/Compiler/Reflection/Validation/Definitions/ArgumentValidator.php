<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Validation\Definitions;

use Railt\Compiler\Exceptions\TypeConflictException;
use Railt\Reflection\Contracts\Behavior\Inputable;
use Railt\Reflection\Contracts\Definitions\Definition;
use Railt\Reflection\Contracts\Dependent\ArgumentDefinition;

/**
 * Class ArgumentValidator
 */
class ArgumentValidator extends BaseDefinitionValidator
{
    /**
     * @param Definition $definition
     * @return bool
     */
    public function match(Definition $definition): bool
    {
        return $definition instanceof ArgumentDefinition;
    }

    /**
     * @param Definition|ArgumentDefinition $type
     * @return void
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    public function validate(Definition $type): void
    {
        $definition = $type->getTypeDefinition();

        if (! ($definition instanceof Inputable)) {
            $error = \sprintf('%s must be type of Scalar, Enum or Input', $type);
            throw new TypeConflictException($error, $this->getCallStack());
        }

        if ($type->hasDefaultValue()) {
            $this->validateDefaultValue($type, $definition);
        }
    }

    /**
     * @param ArgumentDefinition $type
     * @param Inputable $definition
     * @return void
     * @throws TypeConflictException
     */
    private function validateDefaultValue(ArgumentDefinition $type, Inputable $definition): void
    {
        $default = $type->getDefaultValue();

        if ($default === null) {
            $this->validateNullDefaultValue($type);

            return;
        }

        if (\is_array($default)) {
            $this->validateArrayDefaultValue($type, $default);

            $this->validateDefaultListType($type, $definition, $default);

            return;
        }

        $this->validateDefaultType($type, $definition, $default);
    }

    /**
     * @param ArgumentDefinition $type
     * @return void
     * @throws TypeConflictException
     */
    private function validateNullDefaultValue(ArgumentDefinition $type): void
    {
        if ($type->isNonNull()) {
            $error = \sprintf('%s can not be initialized by default value NULL', $type);

            throw new TypeConflictException($error, $this->getCallStack());
        }
    }

    /**
     * @param ArgumentDefinition $type
     * @param array $defaults
     * @return void
     * @throws TypeConflictException
     */
    private function validateArrayDefaultValue(ArgumentDefinition $type, array $defaults): void
    {
        if (! $type->isList()) {
            $error = \sprintf('%s can not be initialized by List "%s"',
                $type,
                $this->valueToString($defaults)
            );
            throw new TypeConflictException($error, $this->getCallStack());
        }


        if ($type->isList() && $type->isListOfNonNulls()) {
            foreach ($defaults as $value) {
                if ($value === null) {
                    $error = \sprintf('%s can not be initialized by list "%s" with NULL value',
                        $type,
                        $this->valueToString($defaults)
                    );

                    throw new TypeConflictException($error, $this->getCallStack());
                }
            }
        }
    }

    /**
     * @param ArgumentDefinition $type
     * @param Inputable $definition
     * @param array $values
     * @return void
     * @throws TypeConflictException
     */
    private function validateDefaultListType(ArgumentDefinition $type, Inputable $definition, array $values): void
    {
        $isNullable = ! $type->isListOfNonNulls();

        foreach ($values as $value) {
            $isNull = $isNullable && $value === null;

            if (! $isNull && ! $definition->isCompatible($value)) {
                $error = \sprintf('%s defined by %s can not be initialized by %s',
                    $type,
                    $this->typeIndicatorToString($type),
                    $this->valueToString($values)
                );

                throw new TypeConflictException($error, $this->getCallStack());
            }
        }
    }

    /**
     * @param ArgumentDefinition $type
     * @param Inputable $definition
     * @param $value
     * @return void
     * @throws TypeConflictException
     */
    private function validateDefaultType(ArgumentDefinition $type, Inputable $definition, $value): void
    {
        if (! $definition->isCompatible($value)) {
            $error = \sprintf('%s contain non compatible default value %s', $type, $this->valueToString($value));
            throw new TypeConflictException($error, $this->getCallStack());
        }
    }
}
