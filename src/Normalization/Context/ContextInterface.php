<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Normalization\Context;

use Railt\SDL\Contracts\Definitions\TypeDefinition;

/**
 * Interface ContextInterface
 */
interface ContextInterface
{
    /**
     * @return TypeDefinition
     */
    public function getType(): TypeDefinition;

    /**
     * @return bool
     */
    public function isScalar(): bool;

    /**
     * @return bool
     */
    public function isList(): bool;

    /**
     * @return bool
     */
    public function isNonNull(): bool;
}
