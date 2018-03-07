<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Runtime;

/**
 * Trait Observer
 * @mixin Observable
 */
trait Observer
{
    /**
     * @var array|\Closure[]
     */
    private $observers = [];

    /**
     * @param \Closure $observer
     * @param bool $prepend
     * @return Observable
     */
    public function subscribe(\Closure $observer, bool $prepend = false): Observable
    {
        if ($prepend) {
            \array_unshift($this->observers, $observer);
        } else {
            $this->observers[] = $observer;
        }

        return $this;
    }

    /**
     * @param mixed $payload
     * @param bool $overwrite
     * @return mixed
     */
    protected function notify($payload, bool $overwrite = false)
    {
        foreach ($this->observers as $observer) {
            if ($overwrite && ($data = $observer($payload)) !== null) {
                $payload = $data;
            }
        }

        return $payload;
    }
}
