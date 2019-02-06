<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json;

use Railt\Io\Exception\NotAccessibleException;
use Railt\Io\Readable;

/**
 * Class Json
 */
class Json
{
    /**
     * All " are converted to \u0022.
     *
     * @since PHP 5.3.0
     * @see https://php.net/manual/en/json.constants.php
     * @var int
     */
    public const ENCODE_HEX_QUOT = JSON_HEX_QUOT;

    /**
     * All &lt; and &gt; are converted to \u003C and \u003E.
     *
     * @since PHP 5.3.0
     * @see https://php.net/manual/en/json.constants.php
     * @var int
     */
    public const ENCODE_HEX_TAG = JSON_HEX_TAG;

    /**
     * All &#38;#38;s are converted to \u0026.
     *
     * @since PHP 5.3.0
     * @see https://php.net/manual/en/json.constants.php
     * @var int
     */
    public const ENCODE_HEX_AMP = JSON_HEX_AMP;

    /**
     * All ' are converted to \u0027.
     *
     * @since PHP 5.3.0
     * @see https://php.net/manual/en/json.constants.php
     * @var int
     */
    public const ENCODE_HEX_APOS = JSON_HEX_APOS;

    /**
     * Encodes numeric strings as numbers.
     *
     * @since PHP 5.3.3
     * @see https://php.net/manual/en/json.constants.php
     * @var int
     */
    public const ENCODE_NUMERIC_CHECK = JSON_NUMERIC_CHECK;

    /**
     * Use whitespace in returned data to format it.
     *
     * @since PHP 5.4.0
     * @see https://php.net/manual/en/json.constants.php
     * @var int
     */
    public const ENCODE_PRETTY_PRINT = JSON_PRETTY_PRINT;

    /**
     * Don't escape /.
     *
     * @since PHP 5.4.0
     * @see https://php.net/manual/en/json.constants.php
     * @var int
     */
    public const ENCODE_UNESCAPED_SLASHES = JSON_UNESCAPED_SLASHES;

    /**
     * Outputs an object rather than an array when a non-associative array is
     * used. Especially useful when the recipient of the output is expecting
     * an object and the array is empty.
     *
     * @since PHP 5.3.0
     * @see https://php.net/manual/en/json.constants.php
     * @var int
     */
    public const ENCODE_FORCE_OBJECT = JSON_FORCE_OBJECT;

    /**
     * Ensures that float values are always encoded as a float value.
     *
     * @since PHP 5.6.6
     * @see https://php.net/manual/en/json.constants.php
     * @var int
     */
    public const ENCODE_PRESERVE_ZERO_FRACTION = JSON_PRESERVE_ZERO_FRACTION;

    /**
     * Encode multibyte Unicode characters literally (default is to escape as \uXXXX).
     *
     * @since PHP 5.4.0
     * @see https://php.net/manual/en/json.constants.php
     * @var int
     */
    public const ENCODE_UNESCAPED_UNICODE = JSON_UNESCAPED_UNICODE;

    /**
     * If the JSON_PARTIAL_OUTPUT_ON_ERROR option was given, NULL will be
     * encoded in the place of the recursive reference.
     *
     * @since PHP 5.5.0
     * @see https://php.net/manual/en/json.constants.php
     * @var int
     */
    public const ENCODE_PARTIAL_OUTPUT_ON_ERROR = JSON_PARTIAL_OUTPUT_ON_ERROR;

    /**
     * The line terminators are kept unescaped when JSON_UNESCAPED_UNICODE is
     * supplied. It uses the same behaviour as it was before PHP 7.1 without
     * this constant.
     *
     * @since PHP 7.1.0
     * @see https://php.net/manual/en/json.constants.php
     * @var int
     */
    public const ENCODE_UNESCAPED_LINE_TERMINATORS = JSON_UNESCAPED_LINE_TERMINATORS;

    /**
     * Throws JsonException if an error occurs instead of setting the global
     * error state that is retrieved with json_last_error().
     * JSON_PARTIAL_OUTPUT_ON_ERROR takes precedence over
     * JSON_THROW_ON_ERROR.
     *
     * @since PHP 7.3.0
     * @see https://php.net/manual/en/json.constants.php
     * @var int
     */
    public const THROW_ON_ERROR = JSON_THROW_ON_ERROR;

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
     * Encode <, >, ', &, and " characters in the JSON, making it also safe to
     * be embedded into HTML.
     *
     * Note: ENCODE_HEX_TAG | ENCODE_HEX_APOS | ENCODE_HEX_AMP | ENCODE_HEX_QUOT = 15
     *
     * @var int
     */
    public const DEFAULT_ENCODE_OPTIONS = 15 | self::THROW_ON_ERROR;

    /**
     * Automatically enables object to array convertation.
     *
     * Note: DECODE_OBJECT_AS_ARRAY = 1
     *
     * @var int
     */
    public const DEFAULT_DECODE_OPTIONS = 1 | self::THROW_ON_ERROR;

    /**
     * User specified recursion depth default value.
     *
     * @var int
     */
    public const DEFAULT_DEPTH = 64;

    /**
     * @var Json|null
     */
    private static $instance;

    /**
     * Bitmask of given json encoding options.
     *
     * @var int
     */
    private $encodeOptions;

    /**
     *  Bitmask of given json decoding options.
     *
     * @var int
     */
    private $decodeOptions;

    /**
     * User specified recursion depth.
     *
     * @var int
     */
    private $depth;

    /**
     * Json constructor.
     *
     * @param int $depth
     */
    public function __construct(int $depth = self::DEFAULT_DEPTH)
    {
        $this->depth = $depth;
    }

    /**
     * @return Json|static
     */
    public static function make(): self
    {
        return self::$instance ?? static::new();
    }

    /**
     * @return Json|static
     */
    public static function new(): self
    {
        return static::setInstance(new static());
    }

    /**
     * @param Json|null $instance
     * @return Json|null
     */
    public static function setInstance(self $instance = null): ?self
    {
        self::$instance = $instance;

        return self::$instance;
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
     * @param Readable $readable
     * @return array
     * @throws \JsonException
     */
    public function read(Readable $readable): array
    {
        return $this->decode($readable->getContents());
    }

    /**
     * Wrapper for json_decode with predefined options that throws
     * a \JsonException when an error occurs.
     *
     * @see http://www.php.net/manual/en/function.json-decode.php
     * @see http://php.net/manual/en/class.jsonexception.php
     * @param string $json
     * @return array
     * @throws \JsonException
     */
    public function decode(string $json): array
    {
        return $this->wrap(function () use ($json) {
            return @\json_decode($json, true, $this->depth, $this->getDecodeOptions());
        });
    }

    /**
     * @param \Closure $expression
     * @return mixed
     * @throws \JsonException
     */
    private function wrap(\Closure $expression)
    {
        try {
            $result = $expression();

            // Since PHP >= 7.3 parsing json containing errors can throws
            // an exception. It is necessary to handle these cases.
        } catch (\Throwable $e) {
            if (\get_class($e) === 'Exception' && \strpos($e->getMessage(), 'Failed calling ') === 0) {
                $e = $e->getPrevious() ?: $e;
            }

            throw new \JsonException($e->getMessage(), $e->getCode(), $e);
        }

        // If PHP is lower or equal to version 7.2, then we must
        // handle the error in the old good way.
        if (\json_last_error() !== \JSON_ERROR_NONE) {
            throw new \JsonException(\json_last_error_msg(), \json_last_error());
        }

        return $result;
    }

    /**
     * @return int
     */
    public function getDecodeOptions(): int
    {
        return $this->encodeOptions;
    }

    /**
     * Sets (overwrites) options used while decoding JSON to PHP array.
     *
     * @param int $options
     * @return Json
     */
    public function setDecodeOptions(int $options): self
    {
        $this->decodeOptions = $options;

        return $this;
    }

    /**
     * @param string $pathname
     * @param array $data
     * @return string
     * @throws NotAccessibleException
     * @throws \JsonException
     */
    public function write(string $pathname, array $data): string
    {
        $json = $this->encode($data);

        $dirname = \dirname($pathname);

        if (! @\mkdir($dirname, 0777, true) && ! \is_dir($dirname)) {
            $error = 'Could not write json file, because directory %s not accessible for writing';
            throw new NotAccessibleException(\sprintf($error, $dirname));
        }

        if (@\file_put_contents($pathname, $this->encode($data), \LOCK_EX) === false) {
            $error = 'Error while writing json into %s file';
            throw new NotAccessibleException(\sprintf($error, $pathname));
        }

        return $json;
    }

    /**
     * Wrapper for JSON encoding logic with predefined options that
     * throws a \JsonException when an error occurs.
     *
     * @see http://www.php.net/manual/en/function.json-encode.php
     * @see http://php.net/manual/en/class.jsonexception.php
     * @param array $data
     * @return string
     * @throws \JsonException
     */
    public function encode(array $data): string
    {
        return $this->wrap(function () use ($data) {
            return @\json_encode($data, $this->getEncodeOptions(), $this->depth);
        });
    }

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
     * @param int $options
     * @return Json
     */
    public function setEncodeOptions(int $options): self
    {
        $this->encodeOptions = $options;

        return $this;
    }

    /**
     * Update options used while encoding data to JSON.
     *
     * @param int $options
     * @return Json
     */
    public function withEncodeOptions(int $options): self
    {
        $this->encodeOptions |= $options;

        return $this;
    }

    /**
     * Update options used while decoding JSON to PHP array.
     *
     * @param int $options
     * @return Json
     */
    public function withDecodeOptions(int $options): self
    {
        $this->encodeOptions |= $options;

        return $this;
    }
}
