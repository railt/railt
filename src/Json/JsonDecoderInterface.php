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
 * Interface JsonDecoderInterface
 */
interface JsonDecoderInterface
{
    /**
     * Decodes large integers as their original string value.
     *
     * @since PHP 5.4.0
     * @see https://php.net/manual/en/json.constants.php
     * @var int
     */
    public const DECODE_BIGINT_AS_STRING = JSON_BIGINT_AS_STRING;

    /**
     * Decodes JSON objects as PHP array. This option can be added automatically
     * by calling json_decode() with the second parameter equal to TRUE.
     *
     * @since PHP 5.4.0
     * @see https://php.net/manual/en/json.constants.php
     * @var int
     */
    public const DECODE_OBJECT_AS_ARRAY = JSON_OBJECT_AS_ARRAY;

    /**
     * Wrapper for json_decode with predefined options that throws
     * a \JsonException when an error occurs.
     *
     * @see http://www.php.net/manual/en/function.json-decode.php
     * @see http://php.net/manual/en/class.jsonexception.php
     * @param string $json
     * @return array|mixed
     * @throws JsonException
     */
    public function decode(string $json);

    /**
     * Determine if a JSON decoding option is set.
     *
     * @param int $option
     * @return bool
     */
    public function hasDecodeOption(int $option): bool;

    /**
     * @return int
     */
    public function getDecodeOptions(): int;

    /**
     * Sets (overwrites) options used while decoding JSON to PHP array.
     *
     * @param int ...$options
     * @return JsonDecoderInterface|$this
     */
    public function setDecodeOptions(int ...$options): self;

    /**
     * Update options used while decoding JSON to PHP array.
     *
     * @param int ...$options
     * @return JsonDecoderInterface|$this
     */
    public function withDecodeOptions(int ...$options): self;

    /**
     * Except options used while decoding JSON to PHP array.
     *
     * @param int ...$options
     * @return JsonDecoderInterface|$this
     */
    public function withoutDecodeOptions(int ...$options): self;
}
