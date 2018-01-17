<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Runtime;

use Railt\Reflection\Contracts\Definitions\TypeDefinition;

/**
 * Interface CallStackInterface
 */
interface CallStackInterface extends \Countable
{
    /**
     * @param TypeDefinition[] ...$definitions
     * @return CallStackInterface
     */
    public function push(TypeDefinition ...$definitions): CallStackInterface;

    /**
     * @param int $size
     * @return CallStackInterface
     */
    public function pop(int $size = 1): CallStackInterface;

    /**
     * @return TypeDefinition|null
     */
    public function last(): ?TypeDefinition;
}
