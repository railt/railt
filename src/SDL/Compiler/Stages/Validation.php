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

/**
 * Class Validation
 */
class Validation extends BaseStage
{
    /**
     * @param Definition $definition
     * @return Definition
     */
    public function resolve($definition): Definition
    {
        return parent::resolve($definition);
    }
}
