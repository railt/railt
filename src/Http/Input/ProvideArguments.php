<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Input;

/**
 * Interface ProvideArguments
 */
interface ProvideArguments extends \Countable, \IteratorAggregate
{
    /**
     * @return array
     */
    public function all(): array;

    /**
     * @param string $argument
     * @param null $default
     * @return mixed
     */
    public function get(string $argument, $default = null);

    /**
     * @param string $argument
     * @return bool
     */
    public function has(string $argument): bool;

    /**
     * @param string $argument
     * @param mixed $value
     * @param bool $rewrite
     * @return ProvideArguments|$this
     */
    public function withArgument(string $argument, $value, bool $rewrite = false): self;

    /**
     * @param array $arguments
     * @param bool $rewrite
     * @return ProvideArguments|$this
     */
    public function withArguments(array $arguments, bool $rewrite = false): self;
}
