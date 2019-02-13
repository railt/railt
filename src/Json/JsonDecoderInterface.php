<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json;

use Railt\Io\Readable;
use Railt\Json\Exception\JsonException;

/**
 * Interface JsonDecoderInterface
 */
interface JsonDecoderInterface extends JsonRuntimeInterface
{
    /**
     * Wrapper for json_decode with predefined options that throws
     * a \JsonException when an error occurs.
     *
     * @see http://www.php.net/manual/en/function.json-decode.php
     * @see http://php.net/manual/en/class.jsonexception.php
     * @param string $json
     * @return array|object|mixed
     * @throws JsonException
     */
    public function decode(string $json);

    /**
     * Reads and parses json data from the specified stream.
     *
     * @param Readable $readable
     * @return array|object|mixed
     * @throws \JsonException
     */
    public function read(Readable $readable);
}
