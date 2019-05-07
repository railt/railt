<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Json;

/**
 * Interface OptionsInterface
 */
interface OptionsInterface
{
    /**
     * Returns int code of registered options.
     *
     * @return int
     */
    public function getOptions(): int;

    /**
     * Determine if an option is set.
     *
     * @param int $option
     * @return bool
     */
    public function hasOption(int $option): bool;

    /**
     * Overrides options.
     *
     * @param int ...$options
     * @return JsonRuntimeInterface|$this
     */
    public function setOptions(int ...$options): self;

    /**
     * Enables or disables the passed option ($option) depending on the
     * second argument $enable.
     *
     * @param int $option
     * @param bool $enable
     * @return JsonRuntimeInterface|$this
     */
    public function setOption(int $option, bool $enable = true): self;

    /**
     * Adds a list of passed options to existing ones.
     *
     * @param int ...$options
     * @return JsonRuntimeInterface|$this
     */
    public function withOptions(int ...$options): self;

    /**
     * Removes the list of passed options from existing ones.
     *
     * @param int ...$options
     * @return JsonRuntimeInterface|$this
     */
    public function withoutOptions(int ...$options): self;
}
