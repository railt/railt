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
 * Interface JsonEncoderInterface
 */
interface JsonEncoderInterface extends JsonRuntimeInterface
{
    /**
     * Wrapper for JSON encoding logic with predefined options that
     * throws a \JsonException when an error occurs.
     *
     * @see http://www.php.net/manual/en/function.json-encode.php
     * @see http://php.net/manual/en/class.jsonexception.php
     * @param mixed $data
     * @return string
     * @throws JsonException
     */
    public function encode($data): string;

    /**
     * Writes transferred data to the specified stream pathname.
     *
     * @param string $pathname
     * @param array $data
     * @return Readable
     */
    public function write(string $pathname, array $data): Readable;

    /**
     * Determine if a JSON encoding option is set.
     *
     * @param int $option
     * @return bool
     */
    public function hasEncodeOption(int $option): bool;

    /**
     * @return int
     */
    public function getEncodeOptions(): int;

    /**
     * @param int ...$options
     * @return JsonEncoderInterface|$this
     */
    public function setEncodeOptions(int ...$options): self;

    /**
     * Update options used while encoding data to JSON.
     *
     * @param int ...$options
     * @return JsonEncoderInterface|$this
     */
    public function withEncodeOptions(int ...$options): self;

    /**
     * Except options used while encoding data to JSON.
     *
     * @param int ...$options
     * @return JsonEncoderInterface|$this
     */
    public function withoutEncodeOptions(int ...$options): self;
}
