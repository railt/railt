<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\Reflection\Validation\Inheritance;

use Railt\GraphQL\Exceptions\TypeConflictException;
use Railt\GraphQL\Reflection\Validation\Base\BaseValidator;
use Railt\Reflection\Contracts\Behavior\AllowsTypeIndication;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Contracts\Dependent\FieldDefinition;

/**
 * Class BaseInheritanceValidator
 */
abstract class BaseInheritanceValidator extends BaseValidator implements InheritanceValidator
{
    /**
     * Returns a Boolean TRUE value that indicates that the data type
     * is responsible for the postcondition (Fields). If FALSE is
     * returned, the precondition (Arguments) must be checked.
     *
     * @param AllowsTypeIndication $parent
     * @return bool
     */
    protected function isPostCondition(AllowsTypeIndication $parent): bool
    {
        return $parent instanceof FieldDefinition;
    }

    /**
     * @param AllowsTypeIndication|TypeDefinition $child
     * @param AllowsTypeIndication|TypeDefinition $parent
     * @return void
     * @throws TypeConflictException
     */
    protected function throwIncompatibleTypes(TypeDefinition $child, TypeDefinition $parent): void
    {
        $error = \vsprintf('%s definition of %s must be a compatible %s, but %s given', [
            $parent->getTypeDefinition(),
            $parent,
            $parent->getTypeDefinition()->getTypeName(),
            $child->getTypeDefinition(),
        ]);

        throw new TypeConflictException($error, $this->getCallStack());
    }

    /**
     * @param AllowsTypeIndication|TypeDefinition $child
     * @param AllowsTypeIndication|TypeDefinition $parent
     * @return bool
     */
    protected function isEqualType(TypeDefinition $child, TypeDefinition $parent): bool
    {
        return $this->isSameType($child, $parent) &&
            $child->getTypeDefinition()->getName() === $parent->getTypeDefinition()->getName();
    }

    /**
     * @param AllowsTypeIndication|TypeDefinition $child
     * @param AllowsTypeIndication|TypeDefinition $parent
     * @return bool
     */
    protected function isSameType(TypeDefinition $child, TypeDefinition $parent): bool
    {
        [$childType, $parentType] = [$child->getTypeDefinition(), $parent->getTypeDefinition()];

        return $childType instanceof $parentType && $parentType instanceof $childType;
    }
}
