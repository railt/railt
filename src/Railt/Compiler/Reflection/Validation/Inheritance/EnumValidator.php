<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Validation\Inheritance;

use Railt\Compiler\Exceptions\TypeConflictException;
use Railt\Compiler\Reflection\Contracts\Behavior\AllowsTypeIndication;
use Railt\Compiler\Reflection\Contracts\Definitions\EnumDefinition;
use Railt\Compiler\Reflection\Contracts\Definitions\TypeDefinition;

/**
 * Class EnumValidator
 */
class EnumValidator extends BaseInheritanceValidator
{
    /**
     * @param AllowsTypeIndication|TypeDefinition $child
     * @param AllowsTypeIndication|TypeDefinition $parent
     * @return bool
     */
    public function match(TypeDefinition $child, TypeDefinition $parent): bool
    {
        return $parent instanceof AllowsTypeIndication &&
            $parent->getTypeDefinition() instanceof EnumDefinition;
    }

    /**
     * @param AllowsTypeIndication|TypeDefinition $child
     * @param AllowsTypeIndication|TypeDefinition $parent
     * @return void
     * @throws TypeConflictException
     */
    public function validate(TypeDefinition $child, TypeDefinition $parent): void
    {
        \assert($parent->getTypeDefinition() instanceof EnumDefinition);

        if (! $this->isEqualType($child, $parent)) {
            $this->throwIncompatibleTypes($child, $parent);
        }
    }
}
