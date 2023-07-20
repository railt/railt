<?php

declare(strict_types=1);

namespace Railt\Http\Factory\Adapter;

use Railt\Http\Factory\Exception\MemoryOverflowException;

final class StreamReader
{
    /**
     * Size of chunk for reading per I/O 'tick'.
     *
     * @var int<1, max>
     */
    public const DEFAULT_STREAM_CHUNK_SIZE = 65536;

    /**
     * Default size of the body (in bytes).
     *
     * The {@see null} value means unlimited and is controlled
     * exclusively by the front-server (nginx) and php.ini value (post_max_size).
     *
     * @link https://www.php.net/manual/ru/ini.core.php#ini.post-max-size
     */
    public const DEFAULT_MAX_BODY_SIZE = null;

    /**
     * @param int<1, max> $readChunkSize Controls the maximum buffer size in
     *        bytes to read at once from the stream.
     *
     *        This value SHOULD NOT be changed unless you know what you're doing.
     *
     *        This can be a positive number which means that up to X bytes will
     *        be read at once from the underlying stream resource. Note that the
     *        actual number of bytes read may be lower if the stream resource
     *        has less than X bytes currently available.
     * @param int<1, max>|null $maxBodySize Controls the maximum body size in
     *        bytes.
     *
     *        This value MUST be greater than of {@see $readChunkSize} or
     *        {@see null} in case of you do not need to control the amount of
     *        data.
     *
     *        In the case of {@see null} value, the amount of transmitted data
     *        will be controlled by the server and the general `post_max_size`
     *        settings in `php.ini` file.
     */
    public function __construct(
        private readonly int $readChunkSize = self::DEFAULT_STREAM_CHUNK_SIZE,
        private readonly ?int $maxBodySize = self::DEFAULT_MAX_BODY_SIZE,
        private readonly bool $fiber = false,
    ) {
        assert($readChunkSize > 0, new \TypeError(
            'Read chunk size must be greater than 0'
        ));

        assert($maxBodySize === null || $maxBodySize > $readChunkSize, new \TypeError(
            'Maximal body size must be greater or equals than read chunk size'
        ));
    }

    /**
     * @param resource $stream
     *
     * @throws \Throwable
     */
    public function read(mixed $stream): string
    {
        assert(\is_resource($stream), new \TypeError(
            \vsprintf('Argument #1 ($stream) must be of type resource stream, %s given', [
                \get_debug_type($stream),
            ])
        ));

        $result = '';
        $length = 0;

        while (!\feof($stream)) {
            $chunk = \fread($stream, $this->readChunkSize);

            if ($this->fiber && \Fiber::getCurrent() !== null) {
                \Fiber::suspend($chunk);
            }

            if ($chunk === false) {
                continue;
            }

            $length += \strlen($chunk);
            $result .= $chunk;

            if ($this->maxBodySize !== null && $length > $this->maxBodySize) {
                throw MemoryOverflowException::fromBodySizeOverflow($this->maxBodySize);
            }
        }

        return $result;
    }
}
