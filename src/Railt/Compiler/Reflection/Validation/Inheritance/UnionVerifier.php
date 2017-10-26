<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Validation\Inheritance;

use Railt\Compiler\Reflection\Contracts\Behavior\AllowsTypeIndication;
use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Reflection\Contracts\Definitions\UnionDefinition;
use Railt\Compiler\Exceptions\TypeConflictException;

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
        /** @var UnionDefinition $union */
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
        return $this->extract($a) instanceof UnionDefinition;
    }

    /**
     * @param UnionDefinition $a
     * @param Definition $b
     * @return bool
     * @throws TypeConflictException
     */
    private function verifyUnionType(UnionDefinition $a, Definition $b): bool
    {
        if ($b instanceof UnionDefinition) {
            return $this->verifySameType($a, $b);
        }

        return $this->verifyUnionInheritance($a, $b);
    }

    /**
     * @param UnionDefinition $a
     * @param UnionDefinition $b
     * @return bool
     * @throws TypeConflictException
     */
    private function verifySameType(UnionDefinition $a, UnionDefinition $b): bool
    {
        if ($a->getName() === $b->getName()) {
            return true;
        }

        return $this->throw('%s can not be redefine by incompatible type %s',
            $this->typeToString($a), $this->typeToString($b));
    }

    /**
     * @param UnionDefinition $a
     * @param Definition $b
     * @return bool
     * @throws TypeConflictException
     */
    private function verifyUnionInheritance(UnionDefinition $a, Definition $b): bool
    {
        if ($a->hasType($b->getName())) {
            return true;
        }

        $error = '%s is not a valid member of %s';
        return $this->throw($error, $this->typeToString($b), $this->typeToString($a));
    }
}
