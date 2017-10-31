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
use Railt\Compiler\Reflection\Contracts\Behavior\Inputable;
use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Reflection\Contracts\Dependent\ArgumentDefinition;

/**
 * Class ArgumentInvocationValidator
 */
class TypeIndication extends AbstractValidator
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
    public function verify(Definition $type): void
    {
        $definition = $type->getTypeDefinition();

        if (! ($definition instanceof Inputable)) {
            $error = \sprintf('%s must be type of Scalar, Enum or Input', $this->typeToString($type));
            throw new TypeConflictException($error);
        }

        if ($type->hasDefaultValue()) {
            $this->checkDefaultValue($type, $definition);
        }
    }

    /**
     * @param ArgumentDefinition $type
     * @param Inputable $definition
     * @return void
     * @throws TypeConflictException
     */
    private function checkDefaultValue(ArgumentDefinition $type, Inputable $definition): void
    {
        $default = $type->getDefaultValue();

        if ($default === null) {
            $this->verifyNullDefaultValue($type);
            return;
        }

        if (\is_array($default)) {
            $this->verifyArrayDefaultValue($type, $default);

            $this->verifyDefaultListType($type, $definition, $default);
            return;
        }

        $this->verifyDefaultType($type, $definition, $default);
    }

    /**
     * @param ArgumentDefinition $type
     * @param Inputable $definition
     * @param array $values
     * @return void
     * @throws TypeConflictException
     */
    private function verifyDefaultListType(ArgumentDefinition $type, Inputable $definition, array $values): void
    {
        $isNullable = ! $type->isListOfNonNulls();

        foreach ($values as $value) {
            $isNull = $isNullable && $value === null;

            if (! $isNull && ! $definition->isCompatible($value)) {
                $error = \sprintf('%s can not be initialized with "%s" default value ' .
                    'because item "%s" is incompatible with the type definition',
                    $this->typeToString($type), $this->valueToString($values), $this->valueWithType($value));
                throw new TypeConflictException($error);
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
    private function verifyDefaultType(ArgumentDefinition $type, Inputable $definition, $value): void
    {
        if (! $definition->isCompatible($value)) {
            $error = \sprintf('%s contain non compatible default value %s',
                $this->typeToString($type), $this->valueToString($value));
            throw new TypeConflictException($error);
        }
    }

    /**
     * @param ArgumentDefinition $type
     * @return void
     * @throws TypeConflictException
     */
    private function verifyNullDefaultValue(ArgumentDefinition $type): void
    {
        if ($type->isNonNull()) {
            $error = \sprintf('%s can not be initialized by default value NULL', $this->typeToString($type));
            throw new TypeConflictException($error);
        }
    }

    /**
     * @param ArgumentDefinition $type
     * @param array $defaults
     * @return void
     * @throws TypeConflictException
     */
    private function verifyArrayDefaultValue(ArgumentDefinition $type, array $defaults): void
    {
        if (! $type->isList()) {
            $error = \sprintf('%s can not be initialized by List "%s"',
                $this->typeToString($type), $this->valueToString($defaults));
            throw new TypeConflictException($error);
        }


        if ($type->isList() && $type->isListOfNonNulls()) {
            foreach ($defaults as $value) {
                if ($value === null) {
                    $error = \sprintf('%s can not be initialized by list "%s" with NULL value',
                        $this->typeToString($type), $this->valueToString($defaults));

                    throw new TypeConflictException($error);
                }
            }
        }
    }
}
