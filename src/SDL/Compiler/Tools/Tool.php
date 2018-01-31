<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Tools;

use Railt\Events\Listenable;
use Railt\SDL\Compiler;

/**
 * Interface Tool
 */
interface Tool extends Listenable
{
    /**
     * @param Compiler $compiler
     * @return Tool
     */
    public function observe(Compiler $compiler): self;
}
