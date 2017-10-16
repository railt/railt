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
use Railt\Reflection\Contracts\Types\ScalarType;
use Railt\Reflection\Exceptions\TypeConflictException;

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
        /** @var ScalarType $type */
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
        return $this->extract($a) instanceof ScalarType;
    }

    /**
     * @param ScalarType $a
     * @param NamedTypeDefinition $b
     * @return bool
     * @throws TypeConflictException
     */
    private function verifyScalar(ScalarType $a, NamedTypeDefinition $b): bool
    {
        $type = \get_class($a);
        $child = \get_class($b);

        if (!($b instanceof $type)) {
            $behavior = $a instanceof $child ? 'wider' : 'incompatible';

            $error = '%s can not be redefined by %s %s';

            return $this->throw($error, $this->typeToString($a), $behavior, $this->typeToString($b));
        }

        return true;
    }
}
