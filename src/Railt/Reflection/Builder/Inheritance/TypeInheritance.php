<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder\Inheritance;

use Railt\Reflection\Contracts\Behavior\AllowsTypeIndication as Type;
use Railt\Reflection\Contracts\Types\InterfaceType;
use Railt\Reflection\Contracts\Types\NamedTypeInterface;
use Railt\Reflection\Contracts\Types\ObjectType;
use Railt\Reflection\Contracts\Types\ScalarType;
use Railt\Reflection\Contracts\Types\UnionType;
use Railt\Reflection\Exceptions\TypeConflictException;

/**
 * Class Inheritance
 */
class TypeInheritance
{
    private const SYNTAX_LIST = '[%s]';
    private const SYNTAX_NON_NULL = '%s!';

    /**
     * @param Type $def
     * @param Type $new
     * @return bool
     * @throws TypeConflictException
     */
    public function checkType(Type $def, Type $new): bool
    {
        $this->checkContainerOverriding($def, $new);

        if (! $this->checkTypeCompatibility($def, $new)) {
            $this->throwIncompatibleError($def->getType(), $new->getType());
        }

        return false;
    }

    /**
     * @param Type $def
     * @param Type $new
     * @return bool
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    private function checkContainerOverriding(Type $def, Type $new): bool
    {
        if (! $def->canBeOverridenBy($new)) {
            $this->throwContainerRedefinitionError($def, $new);
        }

        return true;
    }

    /**
     * @param Type $def
     * @param Type $type
     * @return void
     * @throws TypeConflictException
     */
    private function throwContainerRedefinitionError(Type $def, Type $type): void
    {
        $error = 'Can not override type "%s" by new signature "%s"';
        [$defType, $newType] = [$this->getDefinition($def), $this->getDefinition($type)];

        throw new TypeConflictException(\sprintf($error, $defType, $newType));
    }

    /**
     * @param Type $type
     * @return string
     */
    private function getDefinition(Type $type): string
    {
        $result = $type->getType()->getName();

        if ($type->isNonNull()) {
            $result = \sprintf(self::SYNTAX_NON_NULL, $result);
        }

        if ($type->isList()) {
            $result = \sprintf(self::SYNTAX_LIST, $result);
        }

        if ($type->isNonNullList()) {
            $result = \sprintf(self::SYNTAX_NON_NULL, $result);
        }

        return $result;
    }

    /**
     * @param Type $def
     * @param Type $new
     * @return bool
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    private function checkTypeCompatibility(Type $def, Type $new): bool
    {
        /**
         * @var NamedTypeInterface $original
         * @var NamedTypeInterface $overridden
         */
        [$original, $overridden] = [$def->getType(), $new->getType()];

        /**
         * Check is the same type
         */
        if ($original->getName() === $overridden->getName()) {
            return true;
        }

        /**
         * Check Scalar overriding by other Scalar
         */
        if ($original instanceof ScalarType) {
            $this->checkScalarCompatibility($original, $overridden);
        }

        /**
         * Check Interface overriding by Object
         */
        if (! $this->checkObjectCompatibility($original, $overridden)) {
            return false;
        }

        /**
         * Check Union type overriding by child
         */
        if ($this->checkUnionCompatibility($original, $overridden)) {
            return true;
        }

        return false;
    }

    /**
     * Scalar overriding by other Scalar
     * @todo https://github.com/facebook/graphql/issues/369
     *
     * @param NamedTypeInterface $def
     * @param NamedTypeInterface $new
     * @return bool
     * @throws TypeConflictException
     */
    private function checkScalarCompatibility(NamedTypeInterface $def, NamedTypeInterface $new): bool
    {
        $baseType = \get_class($def);
        $isCompatible = $new instanceof $baseType;

        return $isCompatible ? true : $this->throwScalarOverridingError($def, $new);
    }

    /**
     * @param NamedTypeInterface $def
     * @param NamedTypeInterface $new
     * @return bool
     * @throws TypeConflictException
     */
    private function throwScalarOverridingError(NamedTypeInterface $def, NamedTypeInterface $new): bool
    {
        $error = 'Type Scalar %s can not be overriding by wider or incompatible Scalar %s.';
        $error = \sprintf($error, $def->getName(), $new->getName());

        throw new TypeConflictException($error);
    }

    /**
     * @param NamedTypeInterface $def
     * @param NamedTypeInterface|ObjectType $new
     * @return bool
     */
    private function checkObjectCompatibility(NamedTypeInterface $def, NamedTypeInterface $new): bool
    {
        // Is the definition is Interface and implementation is Object
        $isImplementation = $def instanceof InterfaceType && $new instanceof ObjectType;

        return ! ($isImplementation && ! $new->hasInterface($def->getName()));
    }

    /**
     * @param NamedTypeInterface|UnionType $def
     * @param NamedTypeInterface $new
     * @return bool
     */
    private function checkUnionCompatibility(NamedTypeInterface $def, NamedTypeInterface $new): bool
    {
        $isUnion = $def instanceof UnionType;

        return ! ($isUnion && ! $def->hasType($new->getName()));
    }

    /**
     * @param NamedTypeInterface $def
     * @param NamedTypeInterface $type
     * @return void
     * @throws TypeConflictException
     */
    private function throwIncompatibleError(NamedTypeInterface $def, NamedTypeInterface $type): void
    {
        $original = \sprintf('%s<%s>', $def->getTypeName(), $def->getName());
        $extended = \sprintf('%s<%s>', $type->getTypeName(), $type->getName());
        $error = \sprintf('Type %s not compatible with %s', $original, $extended);

        throw new TypeConflictException($error);
    }
}
