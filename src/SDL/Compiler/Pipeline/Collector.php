<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Pipeline;

use Railt\Compiler\Ast\RuleInterface;
use Railt\SDL\Compiler\SymbolTable;
use Railt\SDL\Compiler\SymbolTable\Builder;

/**
 * Class SimpleStage
 */
class Collector extends BaseStage
{
    /**
     * @return \Traversable|SymbolTable[]
     */
    public function resolve(): \Traversable
    {
        /** @var RuleInterface $next */
        while ($next = $this->pop()) {
            yield (new Builder($next))->build();
        }
    }
}
