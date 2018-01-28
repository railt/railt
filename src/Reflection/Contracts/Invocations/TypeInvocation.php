<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Contracts\Invocations;

use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;

/**
 * Interface TypeInvocation
 */
interface TypeInvocation extends Definition
{
    /**
     * @return string
     */
    public function getTypeName(): string;

    /**
     * @return TypeDefinition
     */
    public function getTypeDefinition(): TypeDefinition;
}
