<?php
/**
 * This file is part of railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Validation\Inheritance;

use Railt\Compiler\Reflection\Contracts\Behavior\AllowsTypeIndication;
use Railt\Compiler\Reflection\Validation\Base\ValidatorsFactory;

/**
 * Class WrappersInheritance
 */
final class Wrappers extends ValidatorsFactory implements InheritanceValidator
{
    /**
     * {@inheritdoc}
     */
    protected const VALIDATOR_CLASSES = [
        Wrappers\ListWrapperValidator::class
    ];

    /**
     * @param AllowsTypeIndication $type
     * @return bool
     */
    public function match($type): bool
    {
        return $type instanceof AllowsTypeIndication;
    }

    /**
     * @return \Closure
     */
    protected function getDefaultMatcher(): \Closure
    {
        return function (Wrappers\WrapperValidator $validator, AllowsTypeIndication $original): bool {
            return $validator->match($original);
        };
    }
}
