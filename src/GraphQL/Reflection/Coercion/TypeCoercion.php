<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\Reflection\Coercion;

use Railt\Reflection\Contracts\Definitions\TypeDefinition;

/**
 * Interface TypeCoercion
 */
interface TypeCoercion
{
    /**
     * @param TypeDefinition $type
     * @return TypeDefinition
     */
    public function apply(TypeDefinition $type): TypeDefinition;
}
