<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Events;

/**
 * Class Events
 */
class Events implements Dispatcher, Observable
{
    /**
     * @var string Any name
     */
    private const T_ANY = '*';

    /**
     * @var array|\Closure[][]
     */
    private $listeners = [];

    /**
     * @var array
     */
    private $keys = [];

    /**
     * @param string $name
     * @return array
     */
    public function events(string $name): array
    {
        $key = $this->key($name);

        $this->prepare($key);

        return $this->listeners[$key];
    }

    /**
     * Format key
     *
     * @param string $name
     * @return string
     */
    private function key(string $name): string
    {
        if (! \array_key_exists($name, $this->keys)) {
            $regex = '/^' . \preg_quote($name, '/') . '$/isu';
            $regex = \str_replace(\preg_quote(self::T_ANY, '/'), '.+?', $regex);

            $this->keys[$name] = $regex;
        }

        return $this->keys[$name];
    }

    /**
     * @param string $key
     */
    private function prepare(string $key): void
    {
        if (! \array_key_exists($key, $this->listeners)) {
            $this->listeners[$key] = [];
        }
    }

    /**
     * @param string $name
     * @param \Closure $then
     * @return Events
     */
    public function listen(string $name, \Closure $then): Listenable
    {
        $key = $this->key($name);

        $this->prepare($key);

        $this->listeners[$key][] = $then;

        return $this;
    }

    /**
     * @param \Closure $observer
     * @param bool $prepend
     * @return Observable
     */
    public function subscribe(\Closure $observer, bool $prepend = false): Observable
    {
        return $this->listen(self::T_ANY, $observer);
    }

    /**
     * Fire an event.
     *
     * @param string $name
     * @param mixed $payload
     * @return mixed|null
     */
    public function dispatch(string $name, $payload)
    {
        $result = null;

        foreach ($this->find($name) as $listener) {
            $output = $listener($name, $payload);

            if ($output !== null) {
                $result = $output;
            }
        }

        return $result;
    }

    /**
     * Find all events by event name.
     *
     * @param string $event
     * @return \Traversable|\Closure[]
     */
    private function find(string $event): \Traversable
    {
        foreach ($this->listeners as $key => $listeners) {
            if (\preg_match($key, $event)) {
                foreach ((array)$listeners as $listener) {
                    yield $event => $listener;
                }
            }
        }
    }
}
