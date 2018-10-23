<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection\Validation\Uniqueness;

use Railt\SDL\Contracts\Definitions\TypeDefinition;

/**
 * Interface TypeUniquenessValidator
 */
interface TypeUniquenessValidator extends UniquenessValidator
{
    /**
     * @param $container
     * @param TypeDefinition $definition
     * @return void
     */
    public function validate($container, TypeDefinition $definition): void;
}
