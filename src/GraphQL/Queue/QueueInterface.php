<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\Queue;

/**
 * Interface QueueInterface
 */
interface QueueInterface
{
    /**
     * @param \Closure $process
     * @param int $priority
     * @return QueueInterface
     */
    public function push(\Closure $process, int $priority = 0): self;

    /**
     * @return void
     */
    public function run(): void;
}
