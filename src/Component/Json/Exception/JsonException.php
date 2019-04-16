<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Json\Exception;

/**
 * Class JsonException
 */
class JsonException extends \JsonException
{
    /**
     * @param int $code
     * @return string|self
     */
    public static function getExceptionByCode(int $code): string
    {
        switch ($code) {
            case \JSON_ERROR_DEPTH:
            case \JSON_ERROR_RECURSION:
                return JsonStackOverflowException::class;

            case \JSON_ERROR_SYNTAX:
            case \JSON_ERROR_STATE_MISMATCH:
                return JsonSyntaxException::class;

            case \JSON_ERROR_UTF8:
            case \JSON_ERROR_UTF16:
            case \JSON_ERROR_CTRL_CHAR:
            case \JSON_ERROR_INF_OR_NAN:
            case \JSON_ERROR_UNSUPPORTED_TYPE:
            case \JSON_ERROR_INVALID_PROPERTY_NAME:
                return JsonEncodingException::class;

            default:
                return self::class;
        }
    }
}
