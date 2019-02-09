<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json;

/**
 * Interface JsonRuntimeInterface
 */
interface JsonRuntimeInterface
{
    /**
     * User specified recursion depth default value.
     *
     * @var int
     */
    public const DEFAULT_RECURSION_DEPTH = 64;

    /**
     * @return int
     */
    public function getRecursionDepth(): int;

    /**
     * @param int $depth
     * @return JsonRuntimeInterface|$this
     */
    public function withRecursionDepth(int $depth): self;

    /**
     * Determine if a JSON decoding and encoding option is set.
     *
     * @param int $option
     * @return bool
     */
    public function hasOption(int $option): bool;

    /**
     * Returns options used while encoding and decoding JSON sources.
     *
     * @return int
     */
    public function getOptions(): int;

    /**
     * Sets (overwrites) options used while encoding and decoding JSON sources.
     *
     * @param int ...$options
     * @return JsonRuntimeInterface|$this
     */
    public function setOptions(int ...$options): self;

    /**
     * Sets option used while encoding and decoding JSON sources.
     *
     * @param int $option
     * @param bool $enable
     * @return JsonRuntimeInterface|$this
     */
    public function setOption(int $option, bool $enable = true): self;

    /**
     * Update options used while encoding and decoding JSON sources.
     *
     * @param int ...$options
     * @return JsonRuntimeInterface|$this
     */
    public function withOptions(int ...$options): self;

    /**
     * Except options used while encoding and decoding JSON sources.
     *
     * @param int ...$options
     * @return JsonRuntimeInterface|$this
     */
    public function withoutOptions(int ...$options): self;
}
