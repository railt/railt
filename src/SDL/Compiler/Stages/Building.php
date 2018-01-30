<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Stages;

use Railt\Reflection\Contracts\Definition;
use Railt\SDL\Compiler\SymbolTable\Record;

/**
 * Class Building
 */
class Building extends BaseStage
{
    /**
     * @param Record $record
     * @return Definition
     */
    public function resolve($record): Definition
    {
        \assert($record instanceof Record);
    }
}
