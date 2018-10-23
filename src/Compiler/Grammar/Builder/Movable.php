<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Builder;

/**
 * Trait Movable
 */
trait Movable
{
    /**
     * @param string|int $to
     * @return Buildable
     */
    public function move($to): Buildable
    {
        \assert(\is_string($to) || \is_int($to));

        $this->id = $to;

        return $this;
    }
}
