<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Contracts\Definitions\Enum;

use Railt\Compiler\Reflection\Contracts\Dependent\DependentDefinition;

/**
 * Interface ValueDefinition
 */
interface ValueDefinition extends DependentDefinition
{
    /**
     * @return string
     */
    public function getValue(): string;
}
