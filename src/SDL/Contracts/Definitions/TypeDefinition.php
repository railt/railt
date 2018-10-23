<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Contracts\Definitions;

use Railt\SDL\Contracts\Behavior\Deprecatable;

/**
 * Interface TypeDefinition
 */
interface TypeDefinition extends Definition, Deprecatable
{
    /**
     * Returns the name of type.
     *
     * @return string
     */
    public function getTypeName(): string;
}
