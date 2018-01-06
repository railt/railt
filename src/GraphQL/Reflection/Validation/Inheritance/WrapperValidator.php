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
use Railt\Reflection\Contracts\Behavior\AllowsTypeIndication;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Contracts\Dependent\DependentDefinition;

/**
 * Validation of arguments and fields inheritance.
 */
class WrapperValidator extends BaseInheritanceValidator
{
    /**
     * @param AllowsTypeIndication|TypeDefinition $child
     * @param AllowsTypeIndication|TypeDefinition $parent
     * @return bool
     */
    public function match(TypeDefinition $child, TypeDefinition $parent): bool
    {
        return $child instanceof AllowsTypeIndication &&
            $parent instanceof AllowsTypeIndication;
    }

    /**
     * @param AllowsTypeIndication|TypeDefinition $child
     * @param AllowsTypeIndication|TypeDefinition $parent
     * @return void
     * @throws \Railt\GraphQL\Exceptions\TypeConflictException
     * @throws \OutOfBoundsException
     */
    public function validate(TypeDefinition $child, TypeDefinition $parent): void
    {
        /**
         * We check that the List type is redefined by the List type
         */
        $this->validateListType($child, $parent);

        /**
         * We check that the Nullable/NonNull reference is correctly
         * overridden by another Nullable/NonNull signature.
         */
        if ($this->isPostCondition($child)) {
            $this->validatePostconditionalInheritance($child, $parent);
        } else {
            $this->validatePreconditionalInheritance($child, $parent);
        }
    }

    /**
     * @param AllowsTypeIndication $child
     * @param AllowsTypeIndication $parent
     * @return void
     * @throws TypeConflictException
     */
    private function validateListType(AllowsTypeIndication $child, AllowsTypeIndication $parent): void
    {
        $this->validateListDirectRedefinition($child, $parent);
        $this->validateListInverseRedefinition($child, $parent);
    }

    /**
     * Checks the following situations:
     * <code>
     *  - [Type]   overriden by Type
     *  - [Type]   overriden by Type!
     *  - [Type]!  overriden by Type
     *  - [Type]!  overriden by Type!
     *  - [Type!]  overriden by Type
     *  - [Type!]  overriden by Type!
     *  - [Type!]! overriden by Type
     *  - [Type!]! overriden by Type!
     * </code>
     *
     * @param AllowsTypeIndication $child
     * @param AllowsTypeIndication $parent
     * @return void
     * @throws TypeConflictException
     */
    private function validateListDirectRedefinition(AllowsTypeIndication $child, AllowsTypeIndication $parent): void
    {
        $isBrokenDirectRedefinition = $parent->isList() && ! $child->isList();

        if ($isBrokenDirectRedefinition) {
            $error = \sprintf('The %s cannot be overridden by non-list, but %s given', $parent, $child);
            throw new TypeConflictException($error, $this->getCallStack());
        }
    }

    /**
     * Checks the following situations:
     * <code>
     *  - Type  overriden by [Type]
     *  - Type! overriden by [Type]
     *  - Type  overriden by [Type]!
     *  - Type! overriden by [Type]!
     *  - Type  overriden by [Type!]
     *  - Type! overriden by [Type!]
     *  - Type  overriden by [Type!]!
     *  - Type! overriden by [Type!]!
     * </code>
     *
     * @param AllowsTypeIndication $child
     * @param AllowsTypeIndication $parent
     * @return void
     * @throws TypeConflictException
     */
    private function validateListInverseRedefinition(AllowsTypeIndication $child, AllowsTypeIndication $parent): void
    {
        $isBrokenInverseRedefinition = ! $parent->isList() && $child->isList();

        if ($isBrokenInverseRedefinition) {
            $error = \sprintf('The %s cannot be overridden by list, but %s given', $parent, $child);
            throw new TypeConflictException($error, $this->getCallStack());
        }
    }

    /**
     * @param AllowsTypeIndication|DependentDefinition $child
     * @param AllowsTypeIndication|DependentDefinition $parent
     * @return void
     * @throws TypeConflictException
     */
    private function validatePostconditionalInheritance(AllowsTypeIndication $child, AllowsTypeIndication $parent): void
    {
        $invalidWrapperInheritance = $parent->isNonNull() && ! $child->isNonNull();

        $invalidListWrapperInheritance = $this->isSameWrapperList($child, $parent) &&
            $parent->isListOfNonNulls() && ! $child->isListOfNonNulls();

        if ($invalidWrapperInheritance || $invalidListWrapperInheritance) {
            $error = '%s postcondition of %s can not be weakened by %s of %s';
            $error = \sprintf($error, $parent, $parent->getParent(), $child, $child->getParent());

            throw new TypeConflictException($error, $this->getCallStack());
        }
    }

    /**
     * @param AllowsTypeIndication|DependentDefinition $child
     * @param AllowsTypeIndication|DependentDefinition $parent
     * @return void
     * @throws \Railt\GraphQL\Exceptions\TypeConflictException
     */
    private function validatePreconditionalInheritance(AllowsTypeIndication $child, AllowsTypeIndication $parent): void
    {
        $invalidWrapperInheritance = ! $parent->isNonNull() && $child->isNonNull();

        $invalidListWrapperInheritance = $this->isSameWrapperList($child, $parent) &&
            ! $parent->isListOfNonNulls() && $child->isListOfNonNulls();

        if ($invalidWrapperInheritance || $invalidListWrapperInheritance) {
            $error = '%s precondition of %s can not be strengthened by %s of %s';
            $error = \sprintf($error, $parent, $parent->getParent(), $child, $child->getParent());

            throw new TypeConflictException($error, $this->getCallStack());
        }
    }

    /**
     * @param AllowsTypeIndication $child
     * @param AllowsTypeIndication $parent
     * @return bool
     */
    private function isSameWrapperList(AllowsTypeIndication $child, AllowsTypeIndication $parent): bool
    {
        return $child->isList() === $parent->isList();
    }
}
