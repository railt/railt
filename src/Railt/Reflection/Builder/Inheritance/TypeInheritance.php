<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder\Inheritance;

use Railt\Reflection\Builder\Inheritance\TypeVerification\AbstractVerifier;
use Railt\Reflection\Builder\Inheritance\TypeVerification\ContainerVerifier;
use Railt\Reflection\Builder\Inheritance\TypeVerification\InterfaceVerifier;
use Railt\Reflection\Builder\Inheritance\TypeVerification\ScalarVerifier;
use Railt\Reflection\Builder\Inheritance\TypeVerification\UnionVerifier;
use Railt\Reflection\Builder\Inheritance\TypeVerification\Verifier;
use Railt\Reflection\Contracts\Behavior\AllowsTypeIndication;
use Railt\Reflection\Contracts\Types\NamedTypeInterface;
use Railt\Reflection\Exceptions\TypeConflictException;

/**
 * Class Inheritance
 */
class TypeInheritance extends AbstractVerifier
{
    /**
     * @var Verifier|ContainerVerifier
     */
    private $container;

    /**
     * @var array|Verifier[]
     */
    private $rules = [];

    /**
     * TypeInheritance constructor.
     */
    public function __construct()
    {
        $this->container = new ContainerVerifier();

        $this->addRule(new ScalarVerifier(), new InterfaceVerifier(), new UnionVerifier());
    }

    /**
     * @param Verifier[] ...$verifier
     * @return TypeInheritance
     */
    public function addRule(Verifier ...$verifier): TypeInheritance
    {
        foreach ($verifier as $item) {
            $this->rules[] = $item;
        }

        return $this;
    }

    /**
     * @param AllowsTypeIndication $a
     * @param AllowsTypeIndication $b
     * @return bool
     * @throws TypeConflictException
     */
    public function verify(AllowsTypeIndication $a, AllowsTypeIndication $b): bool
    {
        [$def, $new] = [$a->getType(), $b->getType()];

        $this->container->verify($a, $b);

        if (! $this->verifyRules($a, $b)) {
            return $this->throwNonCompatibleTypesException($def, $new);
        }

        return true;
    }

    /**
     * @param AllowsTypeIndication $a
     * @param AllowsTypeIndication $b
     * @return bool
     */
    private function verifyRules(AllowsTypeIndication $a, AllowsTypeIndication $b): bool
    {
        $matched = false;

        foreach ($this->rules as $rule) {
            if (! $rule->match($a, $b)) {
                continue;
            }

            $matched = true;

            if ($rule->verify($a, $b) === false) {
                return false;
            }
        }

        return $matched;
    }

    /**
     * @param NamedTypeInterface $a
     * @param NamedTypeInterface $b
     * @return bool
     * @throws TypeConflictException
     */
    private function throwNonCompatibleTypesException(NamedTypeInterface $a, NamedTypeInterface $b): bool
    {
        $error = '%s not compatible with %s and can not be redefined by';

        return $this->throw($error, $this->typeToString($a), $this->typeToString($b));
    }
}
