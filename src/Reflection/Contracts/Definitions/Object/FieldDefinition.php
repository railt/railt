<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Contracts\Defintions\Object;

use Railt\Reflection\Contracts\Definitions\Common\Dependent;
use Railt\Reflection\Contracts\Definitions\Common\HasTypeIndication;
use Railt\Reflection\Contracts\Definitions\InterfaceDefinition;
use Railt\Reflection\Contracts\Definitions\ObjectDefinition;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;

/**
 * Interface FieldDefinition
 */
interface FieldDefinition extends Dependent, TypeDefinition, HasArguments, HasTypeIndication
{
    /**
     * @return ObjectDefinition|InterfaceDefinition
     */
    public function getParent(): InterfaceDefinition;
}
