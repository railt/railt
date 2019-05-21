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
use Railt\Json\Exception\Message;

/**
 * Class Json
 */
class Json extends Facade
{
    /**
     * @param array|mixed|object $value
     * @param int $options
     * @param int $depth
     * @return string
     * @throws JsonException
     */
    public static function encode($value, int $options = self::DEFAULT_ENCODING_OPTIONS, int $depth = 512): string
    {
        \assert(\function_exists('\\json_encode'), 'PHP JSON extension required');

        return self::wrap(static function () use ($value, $options, $depth) {
            return @\json_encode($value, $options, $depth);
        });
    }

    /**
     * @param string $json
     * @param int $options
     * @param int $depth
     * @return array|mixed|object
     * @throws JsonException
     */
    public static function decode(string $json, int $options = self::DEFAULT_DECODING_OPTIONS, int $depth = 512)
    {
        \assert(\function_exists('\\json_decode'), 'PHP JSON extension required');

        return self::wrap(static function () use ($json, $options, $depth) {
            return @\json_decode($json, (bool)($options & \JSON_OBJECT_AS_ARRAY), $depth, $options);
        });
    }

    /**
     * @param \Closure $expression
     * @return mixed
     * @throws JsonException
     */
    protected static function wrap(\Closure $expression)
    {
        try {
            $result = $expression();
        } catch (\JsonException $e) {
            //
            // Since PHP >= 7.3 parsing json containing errors can throws
            // an exception. It is necessary to handle these cases.
            //
            throw self::throwFromJsonException($e);
        } catch (\Throwable $e) {
            //
            // Other times we may get other (includes generally) errors.
            //
            throw self::throwFromInternal($e);
        }

        // If PHP is lower or equal to version 7.2, then we must
        // handle the error in the old good way.
        if (($errorCode = \json_last_error()) !== \JSON_ERROR_NONE) {
            throw self::throwFromJsonErrorCode($errorCode);
        }

        return $result;
    }

    /**
     * @param \JsonException $original
     * @return JsonException
     */
    private static function throwFromJsonException(\JsonException $original): JsonException
    {
        $exception = JsonException::getExceptionByCode($original->getCode());

        return new $exception(Message::getByException($original), $original->getCode(), $original);
    }

    /**
     * @param \Throwable $e
     * @return JsonException
     */
    private static function throwFromInternal(\Throwable $e): JsonException
    {
        $exception = JsonException::getExceptionByCode($e->getCode());

        if (\get_class($e) === 'Exception' && \strpos($e->getMessage(), 'Failed calling ') === 0) {
            $e = $e->getPrevious() ?: $e;
        }

        throw new $exception($e->getMessage(), $e->getCode(), $e);
    }

    /**
     * @param int $code
     * @return JsonException
     */
    private static function throwFromJsonErrorCode(int $code): JsonException
    {
        $exception = JsonException::getExceptionByCode($code);

        return new $exception(Message::getByCode($code), $code);
    }
}
