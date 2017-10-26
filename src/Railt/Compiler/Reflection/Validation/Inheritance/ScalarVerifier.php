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
use Railt\Compiler\Reflection\Contracts\Definitions\ScalarDefinition;
use Railt\Compiler\Exceptions\TypeConflictException;

/**
 * Scalar overriding by other Scalar
 * @todo https://github.com/facebook/graphql/issues/369
 *
 */
class ScalarVerifier extends AbstractVerifier
{
    /**
     * @param AllowsTypeIndication $a
     * @param AllowsTypeIndication $b
     * @return bool
     * @throws TypeConflictException
     */
    public function verify(AllowsTypeIndication $a, AllowsTypeIndication $b): bool
    {
        /** @var ScalarDefinition $type */
        $type = $this->extract($a);

        return $this->verifyScalar($type, $this->extract($b));
    }

    /**
     * @param AllowsTypeIndication $a
     * @param AllowsTypeIndication $b
     * @return bool
     */
    public function match(AllowsTypeIndication $a, AllowsTypeIndication $b): bool
    {
        return $this->extract($a) instanceof ScalarDefinition;
    }

    /**
     * @param ScalarDefinition $a
     * @param Definition $b
     * @return bool
     * @throws TypeConflictException
     */
    private function verifyScalar(ScalarDefinition $a, Definition $b): bool
    {
        $type = \get_class($a);
        $child = \get_class($b);

        if (!($b instanceof $type)) {
            $behavior = $a instanceof $child ? 'wider' : 'incompatible';

            $error = '%s can not be overridden by %s %s';
            return $this->throw($error, $this->typeToString($a), $behavior, $this->typeToString($b));
        }

        return true;
    }
}
