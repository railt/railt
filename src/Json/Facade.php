<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json;

use Phplrt\Io\File;
use Phplrt\Io\Readable;
use Railt\Json\Exception\JsonException;

/**
 * Class Facade
 */
abstract class Facade implements JsonFacadeInterface
{
    /**
     * @var int
     */
    private const ENC = self::DEFAULT_ENCODING_OPTIONS;

    /**
     * @var int
     */
    private const DEC = self::DEFAULT_DECODING_OPTIONS;

    /**
     * @param Readable $json
     * @param int $options
     * @param int $depth
     * @return array|object
     * @throws JsonException
     */
    public static function read(Readable $json, int $options = self::DEC, int $depth = 512)
    {
        return static::decode($json->getContents(), $options, $depth);
    }

    /**
     * @param string $pathname
     * @param array|object|mixed $value
     * @param int $options
     * @param int $depth
     * @return Readable
     * @throws JsonException
     */
    public static function write(string $pathname, $value, int $options = self::ENC, int $depth = 512): Readable
    {
        $json = static::encode($value, $options, $depth);

        \error_clear_last();

        $result = @\file_put_contents($pathname, $json);

        if (\is_bool($result)) {
            throw new JsonException('Could not write JSON data into ' . $pathname);
        }

        return File::fromPathname($pathname);
    }
}
