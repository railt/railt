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
 * Trait OptionsTrait
 * @mixin OptionsInterface
 */
trait OptionsTrait
{
    /**
     * Bitmask of the given options.
     *
     * @var int
     */
    protected $options = 0;

    /**
     * Returns int code of registered options.
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
     * Determine if an option is set.
     *
     * @param int $option
     * @return bool
     */
    public function hasOption(int $option): bool
    {
        return (bool)($this->options & $option);
    }

    /**
     * Overrides options.
     *
     * @param int ...$options
     * @return OptionsInterface|$this
     */
    public function setOptions(int ...$options): OptionsInterface
    {
        $this->options = 0;

        return $this->withOptions(...$options);
    }

    /**
     * Enables or disables the passed option ($option) depending on the
     * second argument $enable.
     *
     * @param int $option
     * @param bool $enable
     * @return OptionsInterface|$this
     */
    public function setOption(int $option, bool $enable = true): OptionsInterface
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
     * Adds a list of passed options to existing ones.
     *
     * @param int ...$options
     * @return OptionsInterface|$this
     */
    public function withOptions(int ...$options): OptionsInterface
    {
        foreach ($options as $option) {
            $this->options |= $option;
        }

        return $this;
    }

    /**
     * Removes the list of passed options from existing ones.
     *
     * @param int ...$options
     * @return OptionsInterface|$this
     */
    public function withoutOptions(int ...$options): OptionsInterface
    {
        foreach ($options as $option) {
            $this->options &= ~$option;
        }

        return $this;
    }
}
