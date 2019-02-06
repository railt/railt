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
