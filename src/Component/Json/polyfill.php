<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);


if (! \defined('JSON_THROW_ON_ERROR')) {
    /**
     * Throws JsonException if an error occurs instead of setting the global
     * error state that is retrieved with json_last_error().
     * JSON_PARTIAL_OUTPUT_ON_ERROR takes precedence over JSON_THROW_ON_ERROR.
     *
     * @since PHP 7.3.0
     * @see https://php.net/manual/en/json.constants.php
     */
    \define('JSON_THROW_ON_ERROR', 4194304);
}


if (! \class_exists('\\JsonException')) {
    /**
     * Exception thrown if JSON_THROW_ON_ERROR option is set for json_encode()
     * or json_decode().
     * @since PHP 7.3.0
     * @see http://php.net/manual/en/class.jsonexception.php
     */
    class JsonException extends Exception
    {
    }
}
