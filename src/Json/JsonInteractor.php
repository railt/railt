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
 * Class JsonInteractor
 */
class JsonInteractor implements JsonInteractorInterface
{
    /**
     * Bitmask of given json encoding options.
     *
     * @var int
     */
    private $encodeOptions = self::DEFAULT_ENCODE_OPTIONS;

    /**
     * Bitmask of given json decoding options.
     *
     * @var int
     */
    private $decodeOptions = self::DEFAULT_DECODE_OPTIONS;

    /**
     * User specified recursion depth.
     *
     * @var int
     */
    private $depth = self::DEFAULT_DEPTH;

    /**
     * Json constructor.
     *
     * @param int $depth
     */
    public function __construct(int $depth = self::DEFAULT_DEPTH)
    {
        $this->depth = $depth;
    }

    /**
     * Determine if a JSON encoding option is set.
     *
     * @param int $option
     * @return bool
     */
    public function hasEncodeOption(int $option): bool
    {
        return (bool)($this->encodeOptions & $option);
    }

    /**
     * Determine if a JSON decoding option is set.
     *
     * @param int $option
     * @return bool
     */
    public function hasDecodeOption(int $option): bool
    {
        return (bool)($this->decodeOptions & $option);
    }

    /**
     * @param Readable $readable
     * @return array
     * @throws JsonException
     */
    public function read(Readable $readable): array
    {
        return $this->decode($readable->getContents());
    }

    /**
     * Wrapper for json_decode with predefined options that throws
     * a Railt\Json\Exception\JsonException when an error occurs.
     *
     * @see http://www.php.net/manual/en/function.json-decode.php
     * @see http://php.net/manual/en/class.jsonexception.php
     * @param string $json
     * @return array
     * @throws JsonException
     */
    public function decode(string $json): array
    {
        return $this->wrap(function () use ($json) {
            return @\json_decode($json, true, $this->depth, $this->getDecodeOptions());
        });
    }

    /**
     * @param \Closure $expression
     * @return mixed
     * @throws JsonException
     */
    private function wrap(\Closure $expression)
    {
        try {
            $result = $expression();
        } catch (\JsonException $e) {
            //
            // Since PHP >= 7.3 parsing json containing errors can throws
            // an exception. It is necessary to handle these cases.
            //
            throw $this->throwFromJsonException($e);
        } catch (\Throwable $e) {
            //
            // Other times we may get other (includes generally) errors.
            //
            throw $this->throwFromInternal($e);
        }

        // If PHP is lower or equal to version 7.2, then we must
        // handle the error in the old good way.
        if (($errorCode = \json_last_error()) !== \JSON_ERROR_NONE) {
            throw $this->throwFromJsonErrorCode($errorCode);
        }

        return $result;
    }

    /**
     * @param \JsonException $original
     * @return JsonException
     */
    private function throwFromJsonException(\JsonException $original): JsonException
    {
        $exception = JsonException::getExceptionByCode($original->getCode());
        $message = JsonException::getMessageByCode($original->getCode());

        return new $exception($message, $original->getCode(), $original);
    }

    /**
     * @param \Throwable $e
     * @return JsonException
     */
    private function throwFromInternal(\Throwable $e): JsonException
    {
        $exception = JsonException::getExceptionByCode($e->getCode());

        if (\get_class($e) === 'Exception' && \strpos($e->getMessage(), 'Failed calling ') === 0) {
            $e = $e->getPrevious() ?: $e;
        }

        throw new $exception($e->getMessage(), $e->getCode(), $e);
    }

    /**
     * @param int $code
     * @return JsonException
     */
    private function throwFromJsonErrorCode(int $code): JsonException
    {
        $exception = JsonException::getExceptionByCode($code);
        $message = JsonException::getMessageByCode($code);

        return new $exception($message, $code);
    }

    /**
     * @return int
     */
    public function getDecodeOptions(): int
    {
        return $this->encodeOptions;
    }

    /**
     * Sets (overwrites) options used while decoding JSON to PHP array.
     *
     * @param int $options
     * @return JsonDecoderInterface|$this
     */
    public function setDecodeOptions(int $options): JsonDecoderInterface
    {
        $this->decodeOptions = $options;

        return $this;
    }

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
     * Wrapper for JSON encoding logic with predefined options that
     * throws a Railt\Json\Exception\JsonException when an error occurs.
     *
     * @see http://www.php.net/manual/en/function.json-encode.php
     * @see http://php.net/manual/en/class.jsonexception.php
     * @param array $data
     * @return string
     * @throws JsonException
     */
    public function encode(array $data): string
    {
        return $this->wrap(function () use ($data) {
            return @\json_encode($data, $this->getEncodeOptions(), $this->depth);
        });
    }

    /**
     * @return int
     */
    public function getEncodeOptions(): int
    {
        return $this->encodeOptions;
    }

    /**
     * Sets (overwrites) options used while encoding data to JSON.
     *
     * @param int $options
     * @return JsonEncoderInterface|$this
     */
    public function setEncodeOptions(int $options): JsonEncoderInterface
    {
        $this->encodeOptions = $options;

        return $this;
    }

    /**
     * Update options used while encoding data to JSON.
     *
     * @param int $options
     * @return JsonEncoderInterface|$this
     */
    public function withEncodeOptions(int $options): JsonEncoderInterface
    {
        $this->encodeOptions |= $options;

        return $this;
    }

    /**
     * Update options used while decoding JSON to PHP array.
     *
     * @param int $options
     * @return JsonDecoderInterface|$this
     */
    public function withDecodeOptions(int $options): JsonDecoderInterface
    {
        $this->encodeOptions |= $options;

        return $this;
    }
}
