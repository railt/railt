<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base\Behavior;

use Railt\Reflection\Contracts\Behavior\Child;
use Railt\Reflection\Contracts\Behavior\Nameable;

/**
 * Trait BaseChild
 * @mixin Child
 */
trait BaseChild
{
    /**
     * @var Nameable
     */
    protected $parent;

    /**
     * @return Nameable
     */
    public function getParent(): Nameable
    {
        return $this->parent;
    }
}
