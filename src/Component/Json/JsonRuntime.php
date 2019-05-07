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
 * Class JsonRuntime
 */
abstract class JsonRuntime implements JsonRuntimeInterface
{
    use OptionsTrait {
        OptionsTrait::getOptions as traitGetOptions;
    }

    /**
     * User specified recursion depth.
     *
     * @var int
     */
    protected $depth = self::DEFAULT_RECURSION_DEPTH;

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
        $options = $this->traitGetOptions();

        if (! (bool)($options & \JSON_THROW_ON_ERROR)) {
            $options |= \JSON_THROW_ON_ERROR;
        }

        return $options;
    }
}
