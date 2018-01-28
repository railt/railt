<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Contracts\Definitions\Enum;

use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Contracts\Definitions\Common\Dependent;

/**
 * Interface ValueDefinition
 */
interface ValueDefinition extends TypeDefinition, Dependent
{
    /**
     * @return string
     */
    public function getValue(): string;
}
