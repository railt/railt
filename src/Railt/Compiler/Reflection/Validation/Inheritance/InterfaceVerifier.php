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
use Railt\Compiler\Reflection\Contracts\Definitions\InterfaceDefinition;
use Railt\Compiler\Reflection\Contracts\Definitions\ObjectDefinition;
use Railt\Compiler\Exceptions\TypeConflictException;

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
        /** @var InterfaceDefinition $type */
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
        return $this->extract($a) instanceof InterfaceDefinition;
    }

    /**
     * @param InterfaceDefinition $a
     * @param Definition $b
     * @return bool
     * @throws TypeConflictException
     */
    private function verifyInterface(InterfaceDefinition $a, Definition $b): bool
    {
        if ($b instanceof ObjectDefinition) {
            return $this->verifyInheritance($a, $b);
        }

        if ($b instanceof InterfaceDefinition) {
            return $this->verifySameType($a, $b);
        }

        $error = '%s can be overridden only by compatible Object or Interface type, but %s given';
        return $this->throw($error, $this->typeToString($a), $this->typeToString($b));
    }

    /**
     * @param InterfaceDefinition $a
     * @param ObjectDefinition $b
     * @return bool
     * @throws TypeConflictException
     */
    private function verifyInheritance(InterfaceDefinition $a, ObjectDefinition $b): bool
    {
        if ($b->hasInterface($a->getName())) {
            return true;
        }

        $error = '%s must implement an %s';
        return $this->throw($error, $this->typeToString($b), $this->typeToString($a));
    }

    /**
     * @param InterfaceDefinition $a
     * @param InterfaceDefinition $b
     * @return bool
     * @throws TypeConflictException
     */
    private function verifySameType(InterfaceDefinition $a, InterfaceDefinition $b): bool
    {
        if ($a->getName() === $b->getName()) {
            return true;
        }

        $error = '%s can not be overridden by incompatible %s';
        return $this->throw($error, $this->typeToString($a), $this->typeToString($b));
    }
}
