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
    use JsonEncoderTrait;
    use JsonDecoderTrait;

    /**
     * User specified recursion depth.
     *
     * @var int
     */
    protected $depth = self::DEFAULT_DEPTH;

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
     * @param \Closure $expression
     * @return mixed
     * @throws JsonException
     */
    protected function wrap(\Closure $expression)
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
    public function getDepth(): int
    {
        return $this->depth;
    }

    /**
     * @param int $depth
     * @return JsonInteractorInterface|$this
     */
    public function withDepth(int $depth): JsonInteractorInterface
    {
        \assert($depth > 0, 'Depth value should be greater than 0');

        $this->depth = $depth;

        return $this;
    }
}
