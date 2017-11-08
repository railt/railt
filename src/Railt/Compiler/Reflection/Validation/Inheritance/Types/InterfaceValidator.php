<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Validation\Inheritance\Types;

use Railt\Compiler\Reflection\Contracts\Definitions\InterfaceDefinition;
use Railt\Compiler\Reflection\Contracts\Definitions\ObjectDefinition;
use Railt\Compiler\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Compiler\Reflection\Validation\Base\BaseValidator;
use Railt\Compiler\Exceptions\TypeRedefinitionException;

/**
 * Class InterfaceValidator
 */
class InterfaceValidator extends BaseValidator implements TypeValidator
{
    /**
     * @param TypeDefinition $type
     * @return bool
     */
    public function match($type): bool
    {
        return $type instanceof InterfaceDefinition;
    }

    /**
     * @param TypeDefinition|InterfaceDefinition $parent
     * @param TypeDefinition|ObjectDefinition $child
     * @return void
     * @throws \Railt\Compiler\Exceptions\TypeRedefinitionException
     */
    public function validate(TypeDefinition $parent, TypeDefinition $child): void
    {
        \assert($parent instanceof InterfaceDefinition);

        $this->validateChildType($child);
        $this->validateCompatibility($parent, $child);
        $this->validateFieldsImplementation($parent, $child);
    }

    /**
     * @param InterfaceDefinition $interface
     * @param ObjectDefinition $object
     * @return void
     * @throws TypeRedefinitionException
     */
    private function validateFieldsImplementation(InterfaceDefinition $interface, ObjectDefinition $object): void
    {
        foreach ($interface->getFields() as $field) {
            if (! $object->hasField($field->getName())) {
                $error = \sprintf('%s must implement the remaining %s',
                    $this->typeToString($object), $this->typeIndicatorToString($field));

                throw new TypeRedefinitionException($error, $this->getCallStack());
            }
        }
    }

    /**
     * @param TypeDefinition $child
     * @return void
     * @throws TypeRedefinitionException
     */
    private function validateChildType(TypeDefinition $child): void
    {
        if (! $child instanceof ObjectDefinition) {
            $error = \sprintf('%s must be an Object type', $this->typeToString($child));
            throw new TypeRedefinitionException($error, $this->getCallStack());
        }
    }

    /**
     * @param InterfaceDefinition $interface
     * @param ObjectDefinition $object
     * @return void
     * @throws TypeRedefinitionException
     */
    private function validateCompatibility(InterfaceDefinition $interface, ObjectDefinition $object): void
    {
        if (! $object->hasInterface($interface->getName())) {
            $error = \sprintf('%s must be an instance of %s',
                $this->typeToString($object),
                $this->typeToString($interface)
            );

            throw new TypeRedefinitionException($error, $this->getCallStack());
        }
    }
}
