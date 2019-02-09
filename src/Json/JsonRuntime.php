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
 * Class JsonRuntime
 */
abstract class JsonRuntime implements JsonRuntimeInterface
{
    /**
     * User specified recursion depth.
     *
     * @var int
     */
    protected $depth = self::DEFAULT_RECURSION_DEPTH;

    /**
     * Bitmask of given json encoding and decoding options.
     *
     * @var int
     */
    protected $options = 0;

    /**
     * @return int
     */
    public function getRecursionDepth(): int
    {
        return $this->depth;
    }

    /**
     * @param int $depth
     * @return JsonRuntimeInterface|$this
     */
    public function withRecursionDepth(int $depth): JsonRuntimeInterface
    {
        \assert($depth > 0, 'Depth must be greater than zero');

        $this->depth = $depth;

        return $this;
    }

    /**
     * Returns options used while encoding and decoding JSON sources.
     *
     * @return int
     */
    public function getOptions(): int
    {
        $options = $this->options;

        if (! (bool)($options & \JSON_THROW_ON_ERROR)) {
            $options |= \JSON_THROW_ON_ERROR;
        }

        return $options;
    }

    /**
     * Determine if a JSON decoding and encoding option is set.
     *
     * @param int $option
     * @return bool
     */
    public function hasOption(int $option): bool
    {
        return (bool)($this->options & $option);
    }

    /**
     * Sets (overwrites) options used while encoding and decoding JSON sources.
     *
     * @param int ...$options
     * @return JsonRuntimeInterface|$this
     */
    public function setOptions(int ...$options): JsonRuntimeInterface
    {
        $this->options = 0;

        return $this->withOptions(...$options);
    }

    /**
     * Sets option used while encoding and decoding JSON sources.
     *
     * @param int $option
     * @param bool $enable
     * @return JsonRuntimeInterface|$this
     */
    public function setOption(int $option, bool $enable = true): JsonRuntimeInterface
    {
        if ($enable && ! $this->hasOption($option)) {
            $this->withOptions($option);
        }

        if (! $enable && $this->hasOption($option)) {
            $this->withoutOptions($option);
        }

        return $this;
    }

    /**
     * Update options used while encoding and decoding JSON sources.
     *
     * @param int ...$options
     * @return JsonRuntimeInterface|$this
     */
    public function withOptions(int ...$options): JsonRuntimeInterface
    {
        foreach ($options as $option) {
            $this->options |= $option;
        }

        return $this;
    }

    /**
     * Except options used while encoding and decoding JSON sources.
     *
     * @param int ...$options
     * @return JsonRuntimeInterface|$this
     */
    public function withoutOptions(int ...$options): JsonRuntimeInterface
    {
        foreach ($options as $option) {
            $this->options &= ~$option;
        }

        return $this;
    }
}
