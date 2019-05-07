<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Runtime\CallStackRenderer;

/**
 * Interface TraceRenderer
 */
interface TraceRenderer
{
    /**
     * @param int $position
     * @return string
     */
    public function toTraceString(int $position): string;

    /**
     * @return string
     */
    public function toMessageString(): string;

    /**
     * @return string
     */
    public function getFile(): string;

    /**
     * @return int
     */
    public function getLine(): int;

    /**
     * @return int
     */
    public function getColumn(): int;
}
