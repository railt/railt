<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json;

use Railt\Io\Readable;
use Railt\Json\Exception\JsonException;

/**
 * Class JsonDecoder
 */
abstract class JsonDecoder extends JsonRuntime implements JsonDecoderInterface
{
    /**
     * Bitmask of given json decoding options.
     *
     * @var int
     */
    protected $options;

    /**
     * @param Readable $readable
     * @return array
     * @throws JsonException
     */
    public function read(Readable $readable): array
    {
        return $this->decode($readable->getContents());
    }

    /**
     * Determine if a JSON decoding option is set.
     *
     * @param int $option
     * @return bool
     */
    public function hasDecodeOption(int $option): bool
    {
        return (bool)($this->options & $option);
    }

    /**
     * @return int
     */
    public function getDecodeOptions(): int
    {
        return $this->options;
    }

    /**
     * Sets (overwrites) options used while decoding JSON to PHP array.
     *
     * @param int ...$options
     * @return JsonDecoderInterface|$this
     */
    public function setDecodeOptions(int ...$options): JsonDecoderInterface
    {
        $this->options = 0;

        return $this->withDecodeOptions(...$options);
    }

    /**
     * Update options used while decoding JSON to PHP array.
     *
     * @param int ...$options
     * @return JsonDecoderInterface|$this
     */
    public function withDecodeOptions(int ...$options): JsonDecoderInterface
    {
        foreach ($options as $option) {
            $this->options |= $option;
        }

        return $this;
    }

    /**
     * Except options used while decoding JSON to PHP array.
     *
     * @param int ...$options
     * @return JsonDecoderInterface|$this
     */
    public function withoutDecodeOptions(int ...$options): JsonDecoderInterface
    {
        foreach ($options as $option) {
            $this->options &= ~$option;
        }

        return $this;
    }
}
