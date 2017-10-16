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
use Railt\Reflection\Contracts\Types\NamedTypeDefinition;
use Railt\Reflection\Contracts\Types\UnionType;
use Railt\Reflection\Exceptions\TypeConflictException;

/**
 * Class UnionVerifier
 */
class UnionVerifier extends AbstractVerifier
{
    /**
     * @param AllowsTypeIndication $a
     * @param AllowsTypeIndication $b
     * @return bool
     * @throws TypeConflictException
     */
    public function verify(AllowsTypeIndication $a, AllowsTypeIndication $b): bool
    {
        /** @var UnionType $union */
        $union = $this->extract($a);

        return $this->verifyUnionType($union, $this->extract($b));
    }

    /**
     * @param AllowsTypeIndication $a
     * @param AllowsTypeIndication $b
     * @return bool
     */
    public function match(AllowsTypeIndication $a, AllowsTypeIndication $b): bool
    {
        return $this->extract($a) instanceof UnionType;
    }

    /**
     * @param UnionType $a
     * @param NamedTypeDefinition $b
     * @return bool
     * @throws TypeConflictException
     */
    private function verifyUnionType(UnionType $a, NamedTypeDefinition $b): bool
    {
        if ($b instanceof UnionType) {
            return $this->verifySameType($a, $b);
        }

        return $this->verifyUnionInheritance($a, $b);
    }

    /**
     * @param UnionType $a
     * @param UnionType $b
     * @return bool
     * @throws TypeConflictException
     */
    private function verifySameType(UnionType $a, UnionType $b): bool
    {
        if ($a->getName() === $b->getName()) {
            return true;
        }

        return $this->throw('%s can not be redefine by incompatible type %s',
            $this->typeToString($a), $this->typeToString($b));
    }

    /**
     * @param UnionType $a
     * @param NamedTypeDefinition $b
     * @return bool
     * @throws TypeConflictException
     */
    private function verifyUnionInheritance(UnionType $a, NamedTypeDefinition $b): bool
    {
        if ($a->hasType($b->getName())) {
            return true;
        }

        $error = '%s is not a valid member of %s';
        return $this->throw($error, $this->typeToString($b), $this->typeToString($a));
    }
}
