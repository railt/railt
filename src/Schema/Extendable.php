<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Schema;

/**
 * Trait Extendable
 * @package Serafim\Railgun\Schema
 */
trait Extendable
{
    /**
     * @var array
     */
    private $extenders = [];

    /**
     * @param string $action
     * @param \Closure $then
     * @return $this|Extendable
     */
    public function extend(string $action, \Closure $then)
    {
        $this->extenders[$action] = $then;

        return $this;
    }
    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws \BadMethodCallException
     */
    public function __call(string $name, array $arguments = [])
    {
        if (array_key_exists($name, $this->extenders)) {
            return call_user_func_array($this->extenders[$name], $arguments);
        }

        throw new \BadMethodCallException('Method ' . $name . ' does not exists in ' . static::class);
    }
}
