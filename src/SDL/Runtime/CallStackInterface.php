<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Runtime;

use Railt\Reflection\Contracts\Definitions\Definition;

/**
 * Interface CallStackInterface
 */
interface CallStackInterface extends \Countable
{
    /**
     * @param Definition[] ...$definitions
     * @return CallStackInterface
     */
    public function push(Definition ...$definitions): self;

    /**
     * @return Definition|null
     */
    public function pop(): ?Definition;
}
