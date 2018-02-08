<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Runtime;

use Railt\Events\Observable;
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
    public function push(Record ...$records): self;

    /**
     * @param int $size
     * @return Record|null
     */
    public function pop(): ?Record;
}
