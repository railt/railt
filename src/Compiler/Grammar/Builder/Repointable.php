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
 * Trait Repointable
 */
trait Repointable
{
    /**
     * @param array $newPointers
     * @return self
     */
    public function repoint(array $newPointers): self
    {
        $this->children = $newPointers;

        return $this;
    }
}
