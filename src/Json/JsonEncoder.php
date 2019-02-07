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
use Railt\Json\JsonRuntime;

/**
 * Class JsonEncoder
 */
abstract class JsonEncoder extends JsonRuntime implements JsonEncoderInterface
{
    /**
     * @var int
     */
    protected $options;

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

    /**
     * @return int
     */
    public function getEncodeOptions(): int
    {
        return $this->options;
    }

    /**
     * Determine if a JSON encoding option is set.
     *
     * @param int $option
     * @return bool
     */
    public function hasEncodeOption(int $option): bool
    {
        return (bool)($this->options & $option);
    }

    /**
     * Sets (overwrites) options used while encoding data to JSON.
     *
     * @param int ...$options
     * @return JsonEncoderInterface|$this
     */
    public function setEncodeOptions(int ...$options): JsonEncoderInterface
    {
        $this->options = 0;

        return $this->withEncodeOptions(...$options);
    }

    /**
     * Update options used while encoding data to JSON.
     *
     * @param int ...$options
     * @return JsonEncoderInterface|$this
     */
    public function withEncodeOptions(int ...$options): JsonEncoderInterface
    {
        foreach ($options as $option) {
            $this->options |= $option;
        }

        return $this;
    }

    /**
     * Except options used while encoding data to JSON.
     *
     * @param int ...$options
     * @return JsonEncoderInterface|$this
     */
    public function withoutEncodeOptions(int ...$options): JsonEncoderInterface
    {
        foreach ($options as $option) {
            $this->options &= ~$option;
        }

        return $this;
    }
}
