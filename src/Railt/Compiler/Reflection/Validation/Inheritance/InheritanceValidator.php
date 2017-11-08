<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Validation\Inheritance;

use Railt\Compiler\Reflection\Contracts\Behavior\AllowsTypeIndication;
use Railt\Compiler\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Compiler\Reflection\Validation\Base\ValidatorInterface;

/**
 * Interface InheritanceValidator
 */
interface InheritanceValidator extends ValidatorInterface
{
    /**
     * @param AllowsTypeIndication|TypeDefinition $type
     * @return bool
     */
    public function match($type): bool;
}
