<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json\Rfc7159;

use Railt\Json\Exception\JsonException;
use Railt\Json\Json;
use Railt\Json\JsonDecoder;

/**
 * Class NativeJsonDecoder
 */
class NativeJsonDecoder extends JsonDecoder
{
    use ErrorHandlerTrait;

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
        $shouldBeArray = $this->hasOption(\JSON_OBJECT_AS_ARRAY);

        return $this->wrap(function () use ($json, $shouldBeArray) {
            return @\json_decode($json, $shouldBeArray, $this->getRecursionDepth(), $this->getOptions());
        });
    }
}
