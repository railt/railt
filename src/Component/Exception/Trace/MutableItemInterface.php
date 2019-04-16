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
 * Interface MutableItemInterface
 */
interface MutableItemInterface
{
    /**
     * @param string $file
     * @return ItemInterface|$this
     */
    public function withFile(string $file): self;

    /**
     * @param int $line
     * @return ItemInterface|$this
     */
    public function withLine(int $line): self;

    /**
     * @param int $column
     * @return ItemInterface|$this
     */
    public function withColumn(int $column): self;
}
