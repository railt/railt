<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder\Inheritance\TypeVerification;

use Railt\Reflection\Contracts\Behavior\AllowsTypeIndication;
use Railt\Reflection\Contracts\Types\InterfaceType;
use Railt\Reflection\Contracts\Types\NamedTypeDefinition;
use Railt\Reflection\Contracts\Types\ObjectType;
use Railt\Reflection\Exceptions\TypeConflictException;

/**
 * Class InterfaceVerifier
 */
class InterfaceVerifier extends AbstractVerifier
{
    /**
     * @param AllowsTypeIndication $a
     * @param AllowsTypeIndication $b
     * @return bool
     * @throws \LogicException
     */
    public function verify(AllowsTypeIndication $a, AllowsTypeIndication $b): bool
    {
        /** @var InterfaceType $type */
        $type = $this->extract($a);

        return $this->verifyInterface($type, $this->extract($b));
    }

    /**
     * @param AllowsTypeIndication $a
     * @param AllowsTypeIndication $b
     * @return bool
     */
    public function match(AllowsTypeIndication $a, AllowsTypeIndication $b): bool
    {
        return $this->extract($a) instanceof InterfaceType;
    }

    /**
     * @param InterfaceType $a
     * @param NamedTypeDefinition $b
     * @return bool
     * @throws TypeConflictException
     */
    private function verifyInterface(InterfaceType $a, NamedTypeDefinition $b): bool
    {
        if ($b instanceof ObjectType) {
            return $this->verifyInheritance($a, $b);
        }

        if ($b instanceof InterfaceType) {
            return $this->verifySameType($a, $b);
        }

        $error = 'Interface type can be redefine only by Object<*>, but %s given';

        return $this->throw($error, $this->typeToString($b));
    }

    /**
     * @param InterfaceType $a
     * @param ObjectType $b
     * @return bool
     * @throws TypeConflictException
     */
    private function verifyInheritance(InterfaceType $a, ObjectType $b): bool
    {
        if ($b->hasInterface($a->getName())) {
            return true;
        }

        $error = '%s must implement an %s';

        return $this->throw($error, $this->typeToString($b), $this->typeToString($a));
    }

    /**
     * @param InterfaceType $a
     * @param InterfaceType $b
     * @return bool
     * @throws TypeConflictException
     */
    private function verifySameType(InterfaceType $a, InterfaceType $b): bool
    {
        if ($a->getName() === $b->getName()) {
            return true;
        }

        $error = '%s can not be redefine by incompatible %s';

        return $this->throw($error, $this->typeToString($a), $this->typeToString($b));
    }
}
