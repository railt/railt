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
use Railt\Io\File\Physical;
use Railt\Io\Readable;
use Railt\Json\Exception\JsonException;

/**
 * Class JsonEncoder
 */
abstract class JsonEncoder extends JsonRuntime implements JsonEncoderInterface
{
    /**
     * Encode <, >, ', &, and " characters in the JSON, making it also safe to
     * be embedded into HTML.
     *
     * @var int
     */
    protected $options =
        Json::ENCODE_HEX_TAG |
        Json::ENCODE_HEX_APOS |
        Json::ENCODE_HEX_AMP |
        Json::ENCODE_HEX_QUOT |
        Json::ENCODE_PRESERVE_ZERO_FRACTION;

    /**
     * Writes transferred data to the specified stream (pathname).
     *
     * @param string $pathname
     * @param array $data
     * @return Readable
     * @throws NotAccessibleException
     * @throws JsonException
     */
    public function write(string $pathname, array $data): Readable
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

        return new Physical($json, $pathname);
    }
}
