<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Reflection\Coercion;

use Railt\Component\SDL\Contracts\Definitions\TypeDefinition;

/**
 * Class BaseTypeCoercion
 */
abstract class BaseTypeCoercion
{
    /**
     * @param TypeDefinition $type
     * @return bool
     */
    abstract public function match(TypeDefinition $type): bool;

    /**
     * @param TypeDefinition $type
     */
    abstract public function apply(TypeDefinition $type): void;
}
