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
 * Interface Stage
 */
interface Stage
{
    /**
     * @param mixed $data
     * @return Stage
     */
    public function push($data): self;

    /**
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * @return \Traversable
     */
    public function resolve(): \Traversable;
}
