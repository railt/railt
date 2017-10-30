<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Validation;

use Railt\Compiler\Exceptions\TypeConflictException;
use Railt\Compiler\Reflection\Contracts\Behavior\AllowsTypeIndication;
use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Reflection\Validation\Inheritance\AbstractVerifier;
use Railt\Compiler\Reflection\Validation\Inheritance\ContainerVerifier;
use Railt\Compiler\Reflection\Validation\Inheritance\InterfaceVerifier;
use Railt\Compiler\Reflection\Validation\Inheritance\ObjectVerifier;
use Railt\Compiler\Reflection\Validation\Inheritance\ScalarVerifier;
use Railt\Compiler\Reflection\Validation\Inheritance\UnionVerifier;
use Railt\Compiler\Reflection\Validation\Inheritance\Verifier;

/**
 * Class Inheritance
 */
class Inheritance extends AbstractVerifier
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

        $this->addRule(
            new ScalarVerifier(),
            new InterfaceVerifier(),
            new UnionVerifier(),
            new ObjectVerifier()
        );
    }

    /**
     * @param Verifier[] ...$verifier
     * @return Inheritance
     */
    public function addRule(Verifier ...$verifier): Inheritance
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
        [$def, $new] = [$a->getTypeDefinition(), $b->getTypeDefinition()];

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
     * @param Definition $a
     * @param Definition $b
     * @return bool
     * @throws TypeConflictException
     */
    private function throwNonCompatibleTypesException(Definition $a, Definition $b): bool
    {
        $error = '%s not compatible and can not be overriden by type %s';

        return $this->throw($error, $this->typeToString($a), $this->typeToString($b));
    }
}
