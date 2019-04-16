<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Http\Output;

/**
 * Interface ProvideData
 */
interface ProvideData extends \Countable, \IteratorAggregate
{
    /**
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * @param string $key
     * @param mixed $data
     * @return ProvideData|$this
     */
    public function with(string $key, $data): self;

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * @return array
     */
    public function all(): array;

    /**
     * @param ProvideData $data
     * @return ProvideData|$this
     */
    public function withData(self $data): self;
}
