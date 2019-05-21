<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);


namespace Railt\Json {

    use Railt\Json\Exception\JsonException;

    const DECODE_OPT = JsonDecoderInterface::DEFAULT_DECODING_OPTIONS;
    const ENCODE_OPT = JsonEncoderInterface::DEFAULT_ENCODING_OPTIONS;

    if (! \function_exists('\\Railt\\Json\\json5_decode')) {
        /**
         * @param string $json
         * @param bool $assoc
         * @param int $depth
         * @param int $options
         * @return array|mixed|object
         * @throws JsonException|\JsonException
         */
        function json5_decode(string $json, bool $assoc = false, int $depth = 512, int $options = DECODE_OPT)
        {
            $options |= ($assoc ? \JSON_OBJECT_AS_ARRAY : 0);

            return Json5::decode($json, $options, $depth);
        }
    }

    if (! \function_exists('\\Railt\\Json\\json5_encode')) {
        /**
         * @param mixed $value
         * @param int $options
         * @param int $depth
         * @return string
         * @throws JsonException|\JsonException
         */
        function json5_encode($value, int $options = ENCODE_OPT, int $depth = 512): string
        {
            return Json5::encode($value, $options, $depth);
        }
    }

    if (! \function_exists('\\Railt\\Json\\json_decode')) {
        /**
         * @param string $json
         * @param bool $assoc
         * @param int $depth
         * @param int $options
         * @return array|mixed|object
         * @throws JsonException|\JsonException
         */
        function json_decode(string $json, bool $assoc = false, int $depth = 512, int $options = DECODE_OPT)
        {
            $options |= ($assoc ? \JSON_OBJECT_AS_ARRAY : 0);

            return Json::decode($json, $options, $depth);
        }
    }

    if (! \function_exists('\\Railt\\Json\\json_encode')) {
        /**
         * @param mixed $value
         * @param int $options
         * @param int $depth
         * @return string
         * @throws JsonException|\JsonException
         */
        function json_encode($value, int $options = ENCODE_OPT, int $depth = 512): string
        {
            return Json::encode($value, $options, $depth);
        }
    }
}
