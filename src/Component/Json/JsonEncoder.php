<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Json;

use Railt\Component\Io\Exception\NotAccessibleException;
use Railt\Component\Io\File\Physical;
use Railt\Component\Io\File\Virtual;
use Railt\Component\Io\Readable;

/**
 * Class JsonEncoder
 */
abstract class JsonEncoder extends JsonRuntime implements JsonEncoderInterface
{
    /**
     * Encode <, >, ', &, and " characters in the JSON, making it also safe to
     * be embedded into HTML.
     *
     * Note: JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_PRESERVE_ZERO_FRACTION = 1039
     *
     * @var int
     */
    protected $options = 1039;

    /**
     * Writes transferred data to the specified stream (pathname).
     *
     * @param string $pathname
     * @param array $data
     * @return Readable
     * @throws NotAccessibleException
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

        return new Virtual($json, $pathname);
    }
}
