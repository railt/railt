<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Validation;

use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Reflection\Validation\Base\ValidatorsFactory;

/**
 * Class Definitions
 */
final class Definitions extends ValidatorsFactory
{
    /**
     * {@inheritdoc}
     */
    protected const VALIDATOR_CLASSES = [
        Definitions\EnumValidator::class,
        Definitions\ArgumentValidator::class,
        Definitions\ObjectValidator::class,
    ];

    /**
     * @return \Closure
     */
    protected function getDefaultMatcher(): \Closure
    {
        return function (Definitions\DefinitionValidator $validator, Definition $definition): bool {
            return $validator->match($definition);
        };
    }
}
