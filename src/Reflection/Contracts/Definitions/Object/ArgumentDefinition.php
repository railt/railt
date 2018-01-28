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
use Railt\Reflection\Contracts\Definitions\Common\HasDefaultValue;
use Railt\Reflection\Contracts\Definitions\Common\HasTypeIndication;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;

/**
 * Interface ArgumentDefinition
 */
interface ArgumentDefinition extends Dependent, TypeDefinition, HasDefaultValue, HasTypeIndication
{
    /**
     * @return HasArguments|TypeDefinition
     */
    public function getParent(): TypeDefinition;
}
