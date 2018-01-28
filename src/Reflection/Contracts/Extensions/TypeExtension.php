<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Contracts\Extensions;

use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;

/**
 * Interface TypeExtension
 */
interface TypeExtension extends Definition
{
    /**
     * @return TypeDefinition
     */
    public function getRelatedType();

    /**
     * @return TypeDefinition
     */
    public function getInnerType();
}
