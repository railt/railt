<?php
/**
 * This file is part of railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Validation\Inheritance;

use Railt\Compiler\Kernel\CallStack;
use Railt\Compiler\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Compiler\Reflection\Validation\Base\ValidatorsFactory;
use Railt\Compiler\Reflection\Validation\Validator;

/**
 * Class WrappersInheritance
 */
final class Types extends ValidatorsFactory implements InheritanceValidator
{
    /**
     * {@inheritdoc}
     */
    protected const VALIDATOR_CLASSES = [
        Types\InterfaceValidator::class
    ];

    /**
     * @param TypeDefinition $type
     * @return bool
     */
    public function match($type): bool
    {
        return $type instanceof TypeDefinition;
    }

    /**
     * @return \Closure
     */
    protected function getDefaultMatcher(): \Closure
    {
        return function (Types\TypeValidator $validator, TypeDefinition $original): bool {
            return $validator->match($original);
        };
    }
}
