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
 * Trait JsonEncoderTrait
 */
trait JsonEncoderTrait
{
    /**
     * Bitmask of given json encoding options.
     *
     * @var int
     */
    protected $encodeOptions = JsonInteractorInterface::DEFAULT_ENCODE_OPTIONS;

    /**
     * @return JsonInteractor|$this
     */
    public function withHexQuotes(): self
    {
        if (! $this->hasEncodeOption(static::ENCODE_HEX_QUOT)) {
            $this->withEncodeOptions(static::ENCODE_HEX_QUOT);
        }

        return $this;
    }

    /**
     * @return JsonInteractor|$this
     */
    public function withoutHexQuotes(): self
    {
        if ($this->hasEncodeOption(static::ENCODE_HEX_QUOT)) {
            $this->withoutEncodeOptions(static::ENCODE_HEX_QUOT);
        }

        return $this;
    }

    /**
     * @return JsonInteractor|$this
     */
    public function withHexTags(): self
    {
        if (! $this->hasEncodeOption(static::ENCODE_HEX_TAG)) {
            $this->withEncodeOptions(static::ENCODE_HEX_TAG);
        }

        return $this;
    }

    /**
     * @return JsonInteractor|$this
     */
    public function withoutHexTags(): self
    {
        if ($this->hasEncodeOption(static::ENCODE_HEX_TAG)) {
            $this->withoutEncodeOptions(static::ENCODE_HEX_TAG);
        }

        return $this;
    }

    /**
     * @return JsonInteractor|$this
     */
    public function withHexAmps(): self
    {
        if (! $this->hasEncodeOption(static::ENCODE_HEX_AMP)) {
            $this->withEncodeOptions(static::ENCODE_HEX_AMP);
        }

        return $this;
    }

    /**
     * @return JsonInteractor|$this
     */
    public function withoutHexAmps(): self
    {
        if ($this->hasEncodeOption(static::ENCODE_HEX_AMP)) {
            $this->withoutEncodeOptions(static::ENCODE_HEX_AMP);
        }

        return $this;
    }

    /**
     * @return JsonInteractor|$this
     */
    public function withHexApos(): self
    {
        if (! $this->hasEncodeOption(static::ENCODE_HEX_APOS)) {
            $this->withEncodeOptions(static::ENCODE_HEX_APOS);
        }

        return $this;
    }

    /**
     * @return JsonInteractor|$this
     */
    public function withoutHexApos(): self
    {
        if ($this->hasEncodeOption(static::ENCODE_HEX_APOS)) {
            $this->withoutEncodeOptions(static::ENCODE_HEX_APOS);
        }

        return $this;
    }

    /**
     * @return JsonInteractor|$this
     */
    public function withNumericConversion(): self
    {
        if (! $this->hasEncodeOption(static::ENCODE_NUMERIC_CHECK)) {
            $this->withEncodeOptions(static::ENCODE_NUMERIC_CHECK);
        }

        return $this;
    }

    /**
     * @return JsonInteractor|$this
     */
    public function withoutNumericConversion(): self
    {
        if ($this->hasEncodeOption(static::ENCODE_NUMERIC_CHECK)) {
            $this->withoutEncodeOptions(static::ENCODE_NUMERIC_CHECK);
        }

        return $this;
    }

    /**
     * @return JsonInteractor|$this
     */
    public function withFormatting(): self
    {
        if (! $this->hasEncodeOption(static::ENCODE_PRETTY_PRINT)) {
            $this->withEncodeOptions(static::ENCODE_PRETTY_PRINT);
        }

        return $this;
    }

    /**
     * @return JsonInteractor|$this
     */
    public function withoutFormatting(): self
    {
        if ($this->hasEncodeOption(static::ENCODE_PRETTY_PRINT)) {
            $this->withoutEncodeOptions(static::ENCODE_PRETTY_PRINT);
        }

        return $this;
    }

    /**
     * @return JsonInteractor|$this
     */
    public function withEscapingSlashes(): self
    {
        if ($this->hasEncodeOption(static::ENCODE_UNESCAPED_SLASHES)) {
            $this->withoutEncodeOptions(static::ENCODE_UNESCAPED_SLASHES);
        }

        return $this;
    }

    /**
     * @return JsonInteractor|$this
     */
    public function withoutEscapingSlashes(): self
    {
        if (! $this->hasEncodeOption(static::ENCODE_UNESCAPED_SLASHES)) {
            $this->withEncodeOptions(static::ENCODE_UNESCAPED_SLASHES);
        }

        return $this;
    }

    /**
     * @return JsonInteractor|$this
     */
    public function withForcedObjectRepresentation(): self
    {
        if (! $this->hasEncodeOption(static::ENCODE_FORCE_OBJECT)) {
            $this->withEncodeOptions(static::ENCODE_FORCE_OBJECT);
        }

        return $this;
    }

    /**
     * @return JsonInteractor|$this
     */
    public function withoutForcedObjectRepresentation(): self
    {
        if ($this->hasEncodeOption(static::ENCODE_FORCE_OBJECT)) {
            $this->withoutEncodeOptions(static::ENCODE_FORCE_OBJECT);
        }

        return $this;
    }

    /**
     * @return JsonInteractor|$this
     */
    public function withFloatsZeroFraction(): self
    {
        if (! $this->hasEncodeOption(static::ENCODE_PRESERVE_ZERO_FRACTION)) {
            $this->withEncodeOptions(static::ENCODE_PRESERVE_ZERO_FRACTION);
        }

        return $this;
    }

    /**
     * @return JsonInteractor|$this
     */
    public function withoutFloatsZeroFraction(): self
    {
        if ($this->hasEncodeOption(static::ENCODE_PRESERVE_ZERO_FRACTION)) {
            $this->withoutEncodeOptions(static::ENCODE_PRESERVE_ZERO_FRACTION);
        }

        return $this;
    }

    /**
     * @return JsonInteractor|$this
     */
    public function withEscapingUnicode(): self
    {
        if ($this->hasEncodeOption(static::ENCODE_UNESCAPED_UNICODE)) {
            $this->withoutEncodeOptions(static::ENCODE_UNESCAPED_UNICODE);
        }

        return $this;
    }

    /**
     * @return JsonInteractor|$this
     */
    public function withoutEscapingUnicode(): self
    {
        if (! $this->hasEncodeOption(static::ENCODE_UNESCAPED_UNICODE)) {
            $this->withEncodeOptions(static::ENCODE_UNESCAPED_UNICODE);
        }

        return $this;
    }

    /**
     * @return JsonInteractor|$this
     */
    public function withEscapingLineTerminals(): self
    {
        if ($this->hasEncodeOption(static::ENCODE_UNESCAPED_LINE_TERMINATORS)) {
            $this->withoutEncodeOptions(static::ENCODE_UNESCAPED_LINE_TERMINATORS);
        }

        return $this;
    }

    /**
     * @return JsonInteractor|$this
     */
    public function withoutEscapingLineTerminals(): self
    {
        if (! $this->hasEncodeOption(static::ENCODE_UNESCAPED_LINE_TERMINATORS)) {
            $this->withEncodeOptions(static::ENCODE_UNESCAPED_LINE_TERMINATORS);
        }

        return $this;
    }

    /**
     * Determine if a JSON encoding option is set.
     *
     * @param int $option
     * @return bool
     */
    public function hasEncodeOption(int $option): bool
    {
        return (bool)($this->encodeOptions & $option);
    }

    /**
     * Wrapper for JSON encoding logic with predefined options that
     * throws a Railt\Json\Exception\JsonException when an error occurs.
     *
     * @see http://www.php.net/manual/en/function.json-encode.php
     * @see http://php.net/manual/en/class.jsonexception.php
     * @param mixed $data
     * @return string
     * @throws JsonException
     */
    public function encode($data): string
    {
        return $this->wrap(function () use ($data) {
            return @\json_encode($data, $this->getEncodeOptions(), $this->getDepth());
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
    public function getEncodeOptions(): int
    {
        return $this->encodeOptions;
    }

    /**
     * Sets (overwrites) options used while encoding data to JSON.
     *
     * @param int ...$options
     * @return JsonEncoderInterface|$this
     */
    public function setEncodeOptions(int ...$options): JsonEncoderInterface
    {
        $this->encodeOptions = 0;

        return $this->withEncodeOptions(...$options);
    }

    /**
     * Update options used while encoding data to JSON.
     *
     * @param int ...$options
     * @return JsonEncoderInterface|$this
     */
    public function withEncodeOptions(int ...$options): JsonEncoderInterface
    {
        foreach ($options as $option) {
            $this->encodeOptions |= $option;
        }

        return $this;
    }

    /**
     * Except options used while encoding data to JSON.
     *
     * @param int ...$options
     * @return JsonEncoderInterface|$this
     */
    public function withoutEncodeOptions(int ...$options): JsonEncoderInterface
    {
        foreach ($options as $option) {
            $this->encodeOptions &= ~$option;
        }

        return $this;
    }
}
