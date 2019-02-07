<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json\Exception;

/**
 * Class Message
 */
class Message
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE_UNKNOWN = 'Unknown JSON error';

    /**
     * @var string
     */
    public const ERROR_MESSAGE_DEPTH = 'The maximum stack depth has been exceeded';

    /**
     * @var string
     */
    public const ERROR_MESSAGE_STATE_MISMATCH = 'Invalid or malformed JSON string';

    /**
     * @var string
     */
    public const ERROR_MESSAGE_CTRL_CHAR = 'Control character error, possibly incorrectly encoded';

    /**
     * @var string
     */
    public const ERROR_MESSAGE_SYNTAX = 'The JSON string contains a syntax error';

    /**
     * @var string
     */
    public const ERROR_MESSAGE_UTF8 = 'The JSON string contains a malformed UTF-8 characters, possibly incorrectly encoded';

    /**
     * @var string
     */
    public const ERROR_MESSAGE_RECURSION = 'One or more recursive references in the value to be encoded';

    /**
     * @var string
     */
    public const ERROR_MESSAGE_INF_OR_NAN = 'One or more NAN or INF values in the value to be encoded';

    /**
     * @var string
     */
    public const ERROR_MESSAGE_UNSUPPORTED_TYPE = 'A value of a type that cannot be encoded was given';

    /**
     * @var string
     */
    public const ERROR_MESSAGE_INVALID_PROPERTY_NAME = 'A property name that cannot be encoded was given';

    /**
     * @var string
     */
    public const ERROR_MESSAGE_UTF16 = 'he JSON string contains a malformed UTF-16 characters, possibly incorrectly encoded';

    /**
     * @param int $code
     * @return string
     */
    public static function getByCode(int $code): string
    {
        switch ($code) {
            case \JSON_ERROR_DEPTH:
                return self::ERROR_MESSAGE_DEPTH;

            case \JSON_ERROR_STATE_MISMATCH:
                return self::ERROR_MESSAGE_STATE_MISMATCH;

            case \JSON_ERROR_CTRL_CHAR:
                return self::ERROR_MESSAGE_CTRL_CHAR;

            case \JSON_ERROR_SYNTAX:
                return self::ERROR_MESSAGE_SYNTAX;

            case \JSON_ERROR_UTF8:
                return self::ERROR_MESSAGE_UTF8;

            case \JSON_ERROR_RECURSION:
                return self::ERROR_MESSAGE_RECURSION;

            case \JSON_ERROR_INF_OR_NAN:
                return self::ERROR_MESSAGE_INF_OR_NAN;

            case \JSON_ERROR_UNSUPPORTED_TYPE:
                return self::ERROR_MESSAGE_UNSUPPORTED_TYPE;

            case \JSON_ERROR_INVALID_PROPERTY_NAME:
                return self::ERROR_MESSAGE_INVALID_PROPERTY_NAME;

            case \JSON_ERROR_UTF16:
                return self::ERROR_MESSAGE_UTF16;

            default:
                return self::ERROR_MESSAGE_UNKNOWN;
        }
    }

    /**
     * @param \JsonException $exception
     * @return string
     */
    public static function getByException(\JsonException $exception): string
    {
        return static::getByCode($exception->getCode());
    }
}
