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

/**
 * Interface JsonInteractorInterface
 */
interface JsonInteractorInterface extends JsonDecoderInterface, JsonEncoderInterface
{
    /**
     * Encode <, >, ', &, and " characters in the JSON, making it also safe to
     * be embedded into HTML.
     *
     * Note: ENCODE_HEX_TAG | ENCODE_HEX_APOS | ENCODE_HEX_AMP | ENCODE_HEX_QUOT = 15
     *
     * @var int
     */
    public const DEFAULT_ENCODE_OPTIONS = 15;

    /**
     * Automatically enables object to array convertation.
     *
     * Note: DECODE_OBJECT_AS_ARRAY = 1
     *
     * @var int
     */
    public const DEFAULT_DECODE_OPTIONS = 1;

    /**
     * User specified recursion depth default value.
     *
     * @var int
     */
    public const DEFAULT_DEPTH = 64;

    /**
     * Throws JsonException if an error occurs instead of setting the global
     * error state that is retrieved with json_last_error().
     * JSON_PARTIAL_OUTPUT_ON_ERROR takes precedence over
     * JSON_THROW_ON_ERROR.
     *
     * @since PHP 7.3.0
     * @see https://php.net/manual/en/json.constants.php
     * @var int
     */
    public const THROW_ON_ERROR = JSON_THROW_ON_ERROR;

    /**
     * Writes transferred data to the specified stream pathname.
     *
     * @param string $pathname
     * @param array $data
     * @return Readable
     */
    public function write(string $pathname, array $data): Readable;

    /**
     * Reads and parses json data from the specified stream.
     *
     * @param Readable $readable
     * @return array
     * @throws \JsonException
     */
    public function read(Readable $readable): array;

    /**
     * @return int
     */
    public function getDepth(): int;

    /**
     * @param int $depth
     * @return JsonInteractorInterface|$this
     */
    public function withDepth(int $depth): self;
}
