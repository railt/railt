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
 * Class Queue
 */
class Queue implements QueueInterface
{
    /**
     * @var array|\SplQueue[]|array<\Closure>
     */
    private $workers = [];

    /**
     * @param int $priority
     * @return \SplQueue
     */
    private function queue(int $priority): \SplQueue
    {
        if (! isset($this->workers[$priority])) {
            $this->workers[$priority] = new \SplQueue();
        }

        return $this->workers[$priority];
    }

    /**
     * @param \Closure $process
     * @param int $priority
     * @return QueueInterface|$this
     */
    public function push(\Closure $process, int $priority = 0): QueueInterface
    {
        $this->queue($priority)->push($process);

        return $this;
    }

    /**
     * @return \Closure|null
     */
    private function next(): ?\Closure
    {
        foreach ($this->workers as $priority => $queue) {
            if ($queue->count()) {
                return $queue->shift();
            }
        }

        return null;
    }

    /**
     * @return void
     */
    public function run(): void
    {
        \ksort($this->workers);

        while ($worker = $this->next()) {
            $worker();
        }
    }
}
