<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection\Validation\Definitions;

use Railt\Reflection\Contracts\Definitions\Definition;
use Railt\Reflection\Contracts\Definitions\InterfaceDefinition;
use Railt\Reflection\Contracts\Definitions\ObjectDefinition;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Contracts\Dependent\ArgumentDefinition;
use Railt\Reflection\Contracts\Dependent\FieldDefinition;
use Railt\SDL\Exceptions\TypeConflictException;
use Railt\SDL\Reflection\Validation\Inheritance;

/**
 * Class ObjectValidator
 */
class ObjectValidator extends BaseDefinitionValidator
{
    /**
     * @param Definition $definition
     * @return bool
     */
    public function match(Definition $definition): bool
    {
        return $definition instanceof ObjectDefinition;
    }

    /**
     * @param Definition|ObjectDefinition $object
     * @return void
     * @throws TypeConflictException
     * @throws \OutOfBoundsException
     */
    public function validate(Definition $object): void
    {
        foreach ($object->getInterfaces() as $interface) {
            $this->getCallStack()->push($interface);

            $this->validateImplementationType($object, $interface);
            $this->validateFieldsExistence($object, $interface);

            $this->getCallStack()->pop();
        }
    }

    /**
     * @param ObjectDefinition $object
     * @param TypeDefinition $type
     * @return void
     */
    private function validateImplementationType(ObjectDefinition $object, TypeDefinition $type): void
    {
        if ($type instanceof InterfaceDefinition) {
            return;
        }

        $error = 'Only interface can be implemented by the %s, but %s given.';

        throw new TypeConflictException(\sprintf($error, $object, $type), $this->getCallStack());
    }

    /**
     * Make sure that all the interface fields are implemented.
     *
     * @param InterfaceDefinition $interface
     * @param ObjectDefinition $object
     * @return void
     * @throws \OutOfBoundsException
     * @throws \Railt\SDL\Exceptions\TypeConflictException
     */
    private function validateFieldsExistence(ObjectDefinition $object, InterfaceDefinition $interface): void
    {
        foreach ($interface->getFields() as $field) {
            $this->getCallStack()->push($field);

            $exists = $object->hasField($field->getName());

            if (! $exists) {
                $this->throwFieldNotDefined($interface, $object, $field);
            }

            $this->validateFieldCompatibility($field, $object->getField($field->getName()));

            $this->getCallStack()->pop();
        }
    }

    /**
     * @param InterfaceDefinition $i
     * @param ObjectDefinition $o
     * @param FieldDefinition $f
     * @return void
     * @throws TypeConflictException
     */
    private function throwFieldNotDefined(InterfaceDefinition $i, ObjectDefinition $o, FieldDefinition $f): void
    {
        $error = \sprintf('%s must contain the remaining %s of the %s', $o, $f, $i);

        throw new TypeConflictException($error, $this->getCallStack()->push($o));
    }

    /**
     * We are convinced that the fields have a comparable signature of the type.
     *
     * @param FieldDefinition $interface
     * @param FieldDefinition $object
     * @return void
     * @throws \Railt\SDL\Exceptions\TypeConflictException
     * @throws \OutOfBoundsException
     */
    private function validateFieldCompatibility(FieldDefinition $interface, FieldDefinition $object): void
    {
        $this->getValidator(Inheritance::class)->validate($object, $interface);

        $this->validateArgumentExistence($interface, $object);
    }

    /**
     * We are convinced that all the arguments of the parent fields were implemented in the child.
     *
     * @param FieldDefinition $interface
     * @param FieldDefinition $object
     * @return void
     * @throws TypeConflictException
     * @throws \OutOfBoundsException
     */
    private function validateArgumentExistence(FieldDefinition $interface, FieldDefinition $object): void
    {
        foreach ($interface->getArguments() as $argument) {
            $this->getCallStack()->push($argument);

            $exists = $object->hasArgument($argument->getName());
            if (! $exists) {
                $this->throwArgumentNotDefined($interface, $object, $argument);
            }

            $this->validateArgumentCompatibility($argument, $object->getArgument($argument->getName()));

            $this->getCallStack()->pop();
        }
    }

    /**
     * @param FieldDefinition $fi
     * @param FieldDefinition $fo
     * @param ArgumentDefinition $a
     * @return void
     * @throws TypeConflictException
     */
    private function throwArgumentNotDefined(FieldDefinition $fi, FieldDefinition $fo, ArgumentDefinition $a): void
    {
        $error = 'The %s of the %s contains an argument %s, but the %s does not implement it';
        $error = \sprintf($error, $fi, $fi->getParent(), $a, $fo->getParent());

        throw new TypeConflictException($error, $this->getCallStack()->push($fo));
    }

    /**
     * We are convinced that the arguments have a comparable signature of the type.
     *
     * @param ArgumentDefinition $interface
     * @param ArgumentDefinition $object
     * @return void
     * @throws \OutOfBoundsException
     */
    private function validateArgumentCompatibility(ArgumentDefinition $interface, ArgumentDefinition $object): void
    {
        $this->getValidator(Inheritance::class)->validate($object, $interface);
    }
}
