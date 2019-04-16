<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Reflection\Validation\Uniqueness;

/**
 * Interface ScalarUniquenessValidator
 */
interface ScalarUniquenessValidator extends UniquenessValidator
{
    /**
     * @param array $container
     * @param string $item
     * @param string $typeName
     * @return void
     */
    public function validate(array $container, string $item, string $typeName): void;
}
