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
     */
    public function verify(Definition $type): void
    {
        $this->checkTypeDefinition($type);

        if ($type->hasDefaultValue()) {
            $this->checkDefaultValue($type);
        }
    }

    /**
     * This method should not cause any errors.
     *
     * @param ArgumentDefinition $type
     * @return void
     */
    private function checkTypeDefinition(ArgumentDefinition $type): void
    {
        $type->getTypeDefinition();

        return;
    }

    /**
     * @param ArgumentDefinition $type
     * @return void
     * @throws TypeConflictException
     */
    private function checkDefaultValue(ArgumentDefinition $type): void
    {
        $default = $type->getDefaultValue();

        if ($default === null) {
            $this->verifyNullDefaultValue($type);
        }

        if (\is_array($default)) {
            $this->verifyArrayDefaultValue($type, $default);
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
            $error = \sprintf('%s can not be initialized by List "[%s]"',
                $this->typeToString($type), $this->valueToString($defaults));
            throw new TypeConflictException($error);
        }


        if ($type->isList() && $type->isListOfNonNulls()) {
            foreach ($defaults as $value) {
                if ($value === null) {
                    $error = \sprintf('%s can not be initialized by list "[%s]" with NULL value',
                        $this->typeToString($type), $this->valueToString($defaults));

                    throw new TypeConflictException($error);
                }
            }
        }
    }
}
