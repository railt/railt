<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json;

use Phplrt\Io\Readable;
use Railt\Json\Exception\JsonException;

/**
 * Interface JsonDecoderInterface
 */
interface JsonDecoderInterface
{
    /**
     * Automatically enables object to array convertation.
     *
     * <code>
     *  const JSON_THROW_ON_ERROR = 4194304;
     * </code>
     * @var int
     */
    public const DEFAULT_DECODING_OPTIONS = 4194304;

    /**
     * @param string $json
     * @param int $options
     * @param int $depth
     * @return array|object
     * @throws JsonException
     */
    public static function decode(string $json, int $options = self::DEFAULT_DECODING_OPTIONS, int $depth = 512);

    /**
     * @param Readable $json
     * @param int $options
     * @param int $depth
     * @return array|object
     * @throws JsonException
     */
    public static function read(Readable $json, int $options = self::DEFAULT_DECODING_OPTIONS, int $depth = 512);
}
