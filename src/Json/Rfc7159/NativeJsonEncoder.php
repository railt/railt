<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json\Rfc7159;

use Railt\Json\JsonEncoder;
use Railt\Json\Exception\JsonException;

/**
 * Class NativeJsonEncoder
 */
class NativeJsonEncoder extends JsonEncoder
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
     * Encode <, >, ', &, and " characters in the JSON, making it also safe to
     * be embedded into HTML.
     *
     * Note: ENCODE_HEX_TAG | ENCODE_HEX_APOS | ENCODE_HEX_AMP | ENCODE_HEX_QUOT = 15
     *
     * @var int
     */
    public const DEFAULT_ENCODE_OPTIONS =
        self::ENCODE_HEX_TAG |
        self::ENCODE_HEX_APOS |
        self::ENCODE_HEX_AMP |
        self::ENCODE_HEX_QUOT |
        self::ENCODE_PRESERVE_ZERO_FRACTION;

    /**
     * @var int
     */
    protected $options = self::DEFAULT_ENCODE_OPTIONS;

    /**
     * NativeJsonEncoder constructor.
     */
    public function __construct()
    {
        \assert(\function_exists('\\json_encode'), 'PHP JSON extension required');
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
            return @\json_encode($data, $this->getEncodeOptions(), $this->getRecursionDepth());
        });
    }

    /**
     * @return int
     */
    public function getEncodeOptions(): int
    {
        $options = parent::getEncodeOptions();

        if (! $this->hasEncodeOption(\JSON_THROW_ON_ERROR)) {
            $options |= \JSON_THROW_ON_ERROR;
        }

        return $options;
    }
}
