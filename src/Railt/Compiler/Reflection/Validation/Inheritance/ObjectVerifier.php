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
use Railt\Compiler\Reflection\Contracts\Definitions\ObjectDefinition;
use Railt\Compiler\Exceptions\TypeConflictException;

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
        /** @var ObjectDefinition $type */
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
        return $this->extract($a) instanceof ObjectDefinition;
    }

    /**
     * @param ObjectDefinition $a
     * @param Definition $b
     * @return bool
     * @throws TypeConflictException
     */
    private function verifyObject(ObjectDefinition $a, Definition $b): bool
    {
        if ($b instanceof ObjectDefinition) {
            return $this->verifySameType($a, $b);
        }

        $error = 'The type of an Object can be implemented only by exactly the same Object, but %s given.';
        return $this->throw($error, $this->typeToString($b));
    }

    /**
     * @param ObjectDefinition $a
     * @param ObjectDefinition $b
     * @return bool
     * @throws TypeConflictException
     */
    private function verifySameType(ObjectDefinition $a, ObjectDefinition $b): bool
    {
        if ($a->getName() === $b->getName()) {
            return true;
        }

        $error = '%s can not be overridden by incompatible type %s';
        return $this->throw($error, $this->typeToString($a), $this->typeToString($b));
    }
}
