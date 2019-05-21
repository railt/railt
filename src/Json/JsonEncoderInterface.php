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
 * Interface JsonEncoderInterface
 */
interface JsonEncoderInterface
{
    /**
     * Encode <, >, ', &, and " characters in the JSON, making it also safe to
     * be embedded into HTML.
     *
     * <code>
     *  const JSON_HEX_TAG = 1;
     *  const JSON_HEX_APOS = 4;
     *  const JSON_HEX_AMP = 2;
     *  const JSON_HEX_QUOT = 8;
     *  const JSON_PRESERVE_ZERO_FRACTION = 1024;
     *  const JSON_THROW_ON_ERROR = 4194304;
     *
     *  // 1 | 4 | 2 | 8 | 1024 | 4194304 === 4195343
     * </code>
     *
     * @var int
     */
    public const DEFAULT_ENCODING_OPTIONS = 4195343;

    /**
     * @param array|object|mixed $value
     * @param int $options
     * @param int $depth
     * @return string
     * @throws JsonException
     */
    public static function encode($value, int $options = self::DEFAULT_ENCODING_OPTIONS, int $depth = 512): string;

    /**
     * @param string $pathname
     * @param array|object|mixed $value
     * @param int $options
     * @param int $depth
     * @return Readable
     * @throws JsonException
     */
    public static function write(
        string $pathname,
        $value,
        int $options = self::DEFAULT_ENCODING_OPTIONS,
        int $depth = 512
    ): Readable;
}
