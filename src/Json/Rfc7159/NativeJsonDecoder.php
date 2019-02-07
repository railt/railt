<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json\Rfc7159;

use Railt\Json\JsonDecoder;
use Railt\Json\Exception\JsonException;

/**
 * Class NativeJsonDecoder
 */
class NativeJsonDecoder extends JsonDecoder
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
     * Automatically enables object to array convertation.
     *
     * @var int
     */
    public const DEFAULT_DECODE_OPTIONS = self::DECODE_OBJECT_AS_ARRAY;

    /**
     * @var int
     */
    protected $options = self::DEFAULT_DECODE_OPTIONS;

    /**
     * NativeJsonEncoder constructor.
     */
    public function __construct()
    {
        \assert(\function_exists('\\json_decode'), 'PHP JSON extension required');
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
            return @\json_decode($json, $shouldBeArray, $this->getRecursionDepth(), $this->getDecodeOptions());
        });
    }

    /**
     * @return int
     */
    public function getDecodeOptions(): int
    {
        $options = parent::getDecodeOptions();

        if (! $this->hasDecodeOption(\JSON_THROW_ON_ERROR)) {
            $options |= \JSON_THROW_ON_ERROR;
        }

        return $options;
    }
}
