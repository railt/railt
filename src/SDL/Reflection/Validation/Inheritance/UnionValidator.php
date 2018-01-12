<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection\Validation\Inheritance;

use Railt\SDL\Exceptions\TypeConflictException;
use Railt\Reflection\Contracts\Behavior\AllowsTypeIndication;
use Railt\Reflection\Contracts\Definitions\ObjectDefinition;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Contracts\Definitions\UnionDefinition;

/**
 * Class UnionValidator
 */
class UnionValidator extends BaseInheritanceValidator
{
    /**
     * @param AllowsTypeIndication|TypeDefinition $child
     * @param AllowsTypeIndication|TypeDefinition $parent
     * @return bool
     */
    public function match(TypeDefinition $child, TypeDefinition $parent): bool
    {
        return $parent instanceof AllowsTypeIndication &&
            $parent->getTypeDefinition() instanceof UnionDefinition;
    }

    /**
     * @param AllowsTypeIndication|TypeDefinition $child
     * @param AllowsTypeIndication|TypeDefinition $parent
     * @return void
     * @throws TypeConflictException
     */
    public function validate(TypeDefinition $child, TypeDefinition $parent): void
    {
        \assert($parent->getTypeDefinition() instanceof UnionDefinition);

        if ($this->isEqualType($child, $parent)) {
            return;
        }

        $this->validateUnionContainsChild($child, $parent);
    }

    /**
     * @param AllowsTypeIndication|TypeDefinition $child
     * @param AllowsTypeIndication|TypeDefinition $parent
     * @return bool
     */
    private function isUnionContainsChild(TypeDefinition $child, TypeDefinition $parent): bool
    {
        /** @var UnionDefinition $union */
        $union = $parent->getTypeDefinition();

        /** @var TypeDefinition $target */
        $target = $child->getTypeDefinition();

        return $union->hasType($target->getName()) || $this->isUnionContainsInterface($union, $target);
    }

    /**
     * @param UnionDefinition $union
     * @param TypeDefinition $object
     * @return bool
     */
    private function isUnionContainsInterface(UnionDefinition $union, TypeDefinition $object): bool
    {
        if ($object instanceof ObjectDefinition) {
            foreach ($object->getInterfaces() as $interface) {
                if ($union->hasType($interface->getName())) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param TypeDefinition $child
     * @param TypeDefinition $parent
     * @return void
     * @throws TypeConflictException
     */
    private function validateUnionContainsChild(TypeDefinition $child, TypeDefinition $parent): void
    {
        if (! $this->isUnionContainsChild($child, $parent)) {
            $this->throwIncompatibleTypes($child, $parent);
        }
    }
}
