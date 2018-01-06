<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\Reflection\Validation;

use Railt\GraphQL\Reflection\Validation\Base\ValidatorsFactory;

/**
 * Class Uniqueness
 */
final class Uniqueness extends ValidatorsFactory
{
    /**
     * {@inheritdoc}
     */
    protected const VALIDATOR_CLASSES = [
        Uniqueness\Scalar\UniqueValueValidator::class,

        Uniqueness\Type\UniqueDefinitionValidator::class,
        Uniqueness\Type\UniqueCollectionValidator::class,
    ];

    /**
     * @return \Closure
     */
    protected function getDefaultMatcher(): \Closure
    {
        return function (Uniqueness\UniquenessValidator $validator, $container, $item): bool {
            return $validator->match($container, $item);
        };
    }
}
