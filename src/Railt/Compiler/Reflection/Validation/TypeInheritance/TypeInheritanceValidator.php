<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Validation\TypeInheritance;

use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Compiler\Reflection\Validation\Base\ValidatorInterface;

/**
 * Interface TypeInheritanceValidator
 */
interface TypeInheritanceValidator extends ValidatorInterface
{
    /**
     * @param TypeDefinition $parent
     * @return bool
     */
    public function match(TypeDefinition $parent): bool;

    /**
     * @param TypeDefinition $parent
     * @param TypeDefinition $child
     * @return void
     */
    public function validate(TypeDefinition $parent, TypeDefinition $child): void;
}
