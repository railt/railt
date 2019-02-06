<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json;

use Railt\Json\Exception\JsonException;

/**
 * Trait JsonDecoderTrait
 */
trait JsonDecoderTrait
{
    /**
     * Bitmask of given json decoding options.
     *
     * @var int
     */
    protected $decodeOptions = JsonInteractorInterface::DEFAULT_DECODE_OPTIONS;

    /**
     * @return JsonDecoderInterface|$this
     */
    public function mustBeArray(): self
    {
        if (! $this->hasDecodeOption(static::DECODE_OBJECT_AS_ARRAY)) {
            $this->withDecodeOptions(static::DECODE_OBJECT_AS_ARRAY);
        }

        return $this;
    }

    /**
     * @return JsonDecoderInterface|$this
     */
    public function mustBeObject(): self
    {
        if ($this->hasDecodeOption(static::DECODE_OBJECT_AS_ARRAY)) {
            $this->withoutDecodeOptions(static::DECODE_OBJECT_AS_ARRAY);
        }

        return $this;
    }

    /**
     * Determine if a JSON decoding option is set.
     *
     * @param int $option
     * @return bool
     */
    public function hasDecodeOption(int $option): bool
    {
        return (bool)($this->decodeOptions & $option);
    }

    /**
     * Wrapper for json_decode with predefined options that throws
     * a Railt\Json\Exception\JsonException when an error occurs.
     *
     * @see http://www.php.net/manual/en/function.json-decode.php
     * @see http://php.net/manual/en/class.jsonexception.php
     * @param string $json
     * @return mixed
     * @throws JsonException
     */
    public function decode(string $json)
    {
        $shouldBeArray = $this->hasDecodeOption(static::DECODE_OBJECT_AS_ARRAY);

        return $this->wrap(function () use ($json, $shouldBeArray) {
            return @\json_decode($json, $shouldBeArray, $this->getDepth(), $this->getDecodeOptions());
        });
    }

    /**
     * @param \Closure $expression
     * @return mixed
     */
    abstract protected function wrap(\Closure $expression);

    /**
     * @return int
     */
    public function getDecodeOptions(): int
    {
        return $this->decodeOptions;
    }

    /**
     * Sets (overwrites) options used while decoding JSON to PHP array.
     *
     * @param int ...$options
     * @return JsonDecoderInterface|$this
     */
    public function setDecodeOptions(int ...$options): JsonDecoderInterface
    {
        $this->decodeOptions = 0;

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
            $this->decodeOptions |= $option;
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
            $this->decodeOptions &= ~$option;
        }

        return $this;
    }
}
