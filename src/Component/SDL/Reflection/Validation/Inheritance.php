<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Reflection\Validation;

use Railt\Component\SDL\Contracts\Definitions\TypeDefinition;
use Railt\Component\SDL\Reflection\Validation\Base\ValidatorsFactory;

/**
 * Class Inheritance
 *
 * @method validate(TypeDefinition $child, TypeDefinition $parent)
 */
final class Inheritance extends ValidatorsFactory
{
    /**
     * {@inheritdoc}
     */
    protected const VALIDATOR_CLASSES = [
        // X < Field Type
        // X < Argument Type
        Inheritance\WrapperValidator::class,
        // X < Interface Type
        Inheritance\InterfaceValidator::class,
        // X < Scalar Type
        Inheritance\ScalarValidator::class,
        // X < Object Type
        Inheritance\ObjectValidator::class,
        // X < Enum Type
        Inheritance\EnumValidator::class,
        // X < Union Type
        Inheritance\UnionValidator::class,
        // X < Input Type
        Inheritance\InputValidator::class,
    ];

    /**
     * @return \Closure
     */
    protected function getDefaultMatcher(): \Closure
    {
        return function (Inheritance\InheritanceValidator $validator, TypeDefinition $a, TypeDefinition $b): bool {
            return $validator->match($a, $b);
        };
    }
}
