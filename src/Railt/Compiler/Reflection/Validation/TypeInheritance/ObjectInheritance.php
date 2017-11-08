<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Validation\TypeInheritance;

use Railt\Compiler\Reflection\Contracts\Definitions\ObjectDefinition;
use Railt\Compiler\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Compiler\Reflection\Validation\Base\BaseValidator;

/**
 * Class ObjectInheritance
 */
class ObjectInheritance extends BaseValidator implements TypeInheritanceValidator
{
    /**
     * @param TypeDefinition $type
     * @return bool
     */
    public function match(TypeDefinition $type): bool
    {
        return $type instanceof ObjectDefinition;
    }

    /**
     * @param TypeDefinition $parent
     * @param TypeDefinition $child
     * @return void
     */
    public function validate(TypeDefinition $parent, TypeDefinition $child): void
    {
        dd($parent, $child);
    }
}
