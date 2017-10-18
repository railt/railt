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
use Railt\Reflection\Contracts\Types\ObjectType;
use Railt\Reflection\Contracts\Types\NamedTypeDefinition;
use Railt\Reflection\Exceptions\TypeConflictException;

/**
 * Class ObjectVerifier
 */
class ObjectVerifier extends AbstractVerifier
{
    /**
     * @param AllowsTypeIndication $a
     * @param AllowsTypeIndication $b
     * @return bool
     * @throws \LogicException
     */
    public function verify(AllowsTypeIndication $a, AllowsTypeIndication $b): bool
    {
        /** @var ObjectType $type */
        $type = $this->extract($a);

        return $this->verifyObject($type, $this->extract($b));
    }

    /**
     * @param AllowsTypeIndication $a
     * @param AllowsTypeIndication $b
     * @return bool
     */
    public function match(AllowsTypeIndication $a, AllowsTypeIndication $b): bool
    {
        return $this->extract($a) instanceof ObjectType;
    }

    /**
     * @param ObjectType $a
     * @param NamedTypeDefinition $b
     * @return bool
     * @throws TypeConflictException
     */
    private function verifyObject(ObjectType $a, NamedTypeDefinition $b): bool
    {
        if ($b instanceof ObjectType) {
            return $this->verifySameType($a, $b);
        }

        $error = 'Object type can\'t be redefine by Object<*>, but %s given';

        return $this->throw($error, $this->typeToString($b));
    }

    /**
     * @param ObjectType $a
     * @param ObjectType $b
     * @return bool
     * @throws TypeConflictException
     */
    private function verifySameType(ObjectType $a, ObjectType $b): bool
    {
        if ($a->getName() === $b->getName()) {
            return true;
        }

        $error = '%s can not be redefine by incompatible %s';

        return $this->throw($error, $this->typeToString($a), $this->typeToString($b));
    }
}
