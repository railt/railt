<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Pipeline;

/**
 * Class Pipe
 */
class Pipe extends BaseStage
{
    /**
     * @return \Traversable
     */
    public function resolve(): \Traversable
    {
        while ($next = $this->pop()) {
            yield $next;
        }
    }
}
