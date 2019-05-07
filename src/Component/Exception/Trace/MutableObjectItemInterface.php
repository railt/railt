<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Exception\Trace;

/**
 * Interface MutableObjectItemInterface
 */
interface MutableObjectItemInterface
{
    /**
     * @param bool $static
     * @return ObjectItemInterface|$this
     */
    public function withStaticCall(bool $static = true): self;

    /**
     * @param string $class
     * @return ObjectItemInterface|$this
     */
    public function withClass(string $class): self;
}
