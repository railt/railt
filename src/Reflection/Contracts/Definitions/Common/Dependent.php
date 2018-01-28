<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Contracts\Definitions\Common;

use Railt\Reflection\Contracts\Definitions\TypeDefinition;

/**
 * Interface Dependent
 */
interface Dependent
{
    /**
     * @return TypeDefinition
     */
    public function getParent();
}
