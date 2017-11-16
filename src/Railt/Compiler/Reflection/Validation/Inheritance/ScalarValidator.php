<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Validation\Inheritance;

use Railt\Compiler\Exceptions\TypeConflictException;
use Railt\Reflection\Contracts\Behavior\AllowsTypeIndication;
use Railt\Reflection\Contracts\Definitions\ScalarDefinition;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;

/**
 * Class ScalarValidator
 */
class ScalarValidator extends BaseInheritanceValidator
{
    /**
     * @param AllowsTypeIndication|TypeDefinition $child
     * @param AllowsTypeIndication|TypeDefinition $parent
     * @return bool
     */
    public function match(TypeDefinition $child, TypeDefinition $parent): bool
    {
        return $parent instanceof AllowsTypeIndication &&
            $parent->getTypeDefinition() instanceof ScalarDefinition;
    }

    /**
     * @param AllowsTypeIndication|TypeDefinition $child
     * @param AllowsTypeIndication|TypeDefinition $parent
     * @return void
     * @throws TypeConflictException
     */
    public function validate(TypeDefinition $child, TypeDefinition $parent): void
    {
        if ($child->getTypeDefinition() instanceof ScalarDefinition) {
            $this->validateScalarCompatibility($child, $parent);
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
    private function validateScalarCompatibility(TypeDefinition $child, TypeDefinition $parent): void
    {
        $this->isPostCondition($parent)
            ? $this->validateDirectScalarCompatibility($child, $parent)
            : $this->validateInverseScalarCompatibility($child, $parent)
        ;
    }

    /**
     * @param AllowsTypeIndication|TypeDefinition $child
     * @param AllowsTypeIndication|TypeDefinition $parent
     * @return void
     * @throws TypeConflictException
     */
    private function validateDirectScalarCompatibility(TypeDefinition $child, TypeDefinition $parent): void
    {
        $parentType = $parent->getTypeDefinition();

        if (! ($child->getTypeDefinition() instanceof $parentType)) {
            $this->throwScalarIncompatibilityException($child, $parent);
        }
    }

    /**
     * @param AllowsTypeIndication|TypeDefinition $child
     * @param AllowsTypeIndication|TypeDefinition $parent
     * @return void
     * @throws TypeConflictException
     */
    private function validateInverseScalarCompatibility(TypeDefinition $child, TypeDefinition $parent): void
    {
        $childType = $child->getTypeDefinition();

        if (! ($parent->getTypeDefinition() instanceof $childType)) {
            $this->throwScalarIncompatibilityException($child, $parent);
        }
    }

    /**
     * @param AllowsTypeIndication|TypeDefinition $child
     * @param AllowsTypeIndication|TypeDefinition $parent
     * @return void
     * @throws TypeConflictException
     */
    private function throwScalarIncompatibilityException(TypeDefinition $child, TypeDefinition $parent): void
    {
        [$childType, $parentType] = [$child->getTypeDefinition(), $parent->getTypeDefinition()];

        $error = \vsprintf('Scalar %s of %s does not compatible with %s of %s', [
            $childType, $child,
            $parentType, $parent
        ]);

        throw new TypeConflictException($error, $this->getCallStack());
    }
}
