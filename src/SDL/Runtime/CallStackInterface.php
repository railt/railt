<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Runtime;

use Railt\Events\Observable;
use Railt\Reflection\Contracts\Definition;
use Railt\SDL\Compiler\SymbolTable\Record;

/**
 * Interface CallStackInterface
 */
interface CallStackInterface extends \Countable, Observable
{
    /**
     * @param Record[] ...$records
     * @return CallStackInterface
     */
    public function push(Record ...$records): CallStackInterface;

    /**
     * @param int $size
     * @return CallStackInterface
     */
    public function pop(int $size = 1): CallStackInterface;

    /**
     * @return Definition|null
     */
    public function last(): ?Definition;
}
