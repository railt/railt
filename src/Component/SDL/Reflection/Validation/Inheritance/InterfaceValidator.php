<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Reflection\Validation\Inheritance;

use Railt\Component\SDL\Contracts\Behavior\AllowsTypeIndication;
use Railt\Component\SDL\Contracts\Definitions\InterfaceDefinition;
use Railt\Component\SDL\Contracts\Definitions\ObjectDefinition;
use Railt\Component\SDL\Contracts\Definitions\TypeDefinition;
use Railt\Component\SDL\Exceptions\TypeConflictException;

/**
 * Class InterfaceValidator
 */
class InterfaceValidator extends BaseInheritanceValidator
{
    /**
     * @param AllowsTypeIndication|TypeDefinition $child
     * @param AllowsTypeIndication|TypeDefinition $parent
     * @return bool
     */
    public function match(TypeDefinition $child, TypeDefinition $parent): bool
    {
        return $parent instanceof AllowsTypeIndication &&
            $parent->getTypeDefinition() instanceof InterfaceDefinition;
    }

    /**
     * @param AllowsTypeIndication|TypeDefinition $child
     * @param AllowsTypeIndication|TypeDefinition $parent
     * @return void
     * @throws TypeConflictException
     */
    public function validate(TypeDefinition $child, TypeDefinition $parent): void
    {
        \assert($parent->getTypeDefinition() instanceof InterfaceDefinition);

        if ($this->isEqualType($child, $parent)) {
            return;
        }

        if ($child->getTypeDefinition() instanceof ObjectDefinition) {
            $this->validateObjectImplementsInterface($child, $parent);
        } else {
            $this->throwIncompatibleTypes($child, $parent);
        }
    }

    /**
     * @param AllowsTypeIndication|TypeDefinition $child
     * @param AllowsTypeIndication|TypeDefinition $parent
     * @return void
     * @throws TypeConflictException
     */
    private function validateObjectImplementsInterface(TypeDefinition $child, TypeDefinition $parent): void
    {
        /** @var ObjectDefinition $object */
        $object = $child->getTypeDefinition();

        if (! $object->hasInterface($parent->getTypeDefinition()->getName())) {
            $error = \vsprintf('%s in %s definition must be instance of %s', [
                $object, $child,
                $parent->getTypeDefinition(),
            ]);

            throw new TypeConflictException($error, $this->getCallStack());
        }
    }
}
