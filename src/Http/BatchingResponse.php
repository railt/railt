<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Http\Extension\HasExtensions;
use Railt\Http\Response\DebugTrait;
use Railt\Http\Response\ProvideExceptions;
use Railt\Http\Response\ResponseRenderer;

/**
 * Class BatchingResponse
 */
class BatchingResponse implements ResponseInterface
{
    use DebugTrait;
    use HasExtensions;
    use ResponseRenderer;

    /**
     * @var array|ResponseInterface[]
     */
    protected $responses = [];

    /**
     * @var int|null
     */
    protected $statusCode;

    /**
     * BatchingResponse constructor.
     * @param ResponseInterface ...$responses
     */
    public function __construct(ResponseInterface ...$responses)
    {
        foreach ($responses as $response) {
            $this->responses[] = $response;
        }
    }

    /**
     * @return array|ResponseInterface[]
     */
    public function getResponses(): array
    {
        \assert(\count($this->responses) > 0);

        return $this->responses;
    }

    /**
     * @param ResponseInterface $response
     * @return ResponseInterface|$this
     */
    public function withResponse(ResponseInterface $response): self
    {
        $this->responses[] = $response;

        return $this;
    }

    /**
     * @return array|\Throwable[]
     */
    public function getExceptions(): array
    {
        $result = [];

        foreach ($this->getResponses() as $response) {
            foreach ($response->getExceptions() as $exception) {
                $result[] = $exception;
            }
        }

        return $result;
    }

    /**
     * @param \Throwable $exception
     * @return ProvideExceptions
     * @throws \LogicException
     */
    public function withException(\Throwable $exception): ProvideExceptions
    {
        throw new \LogicException('Can not add new exception. The ' . __CLASS__ . ' is immutable.');
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        $errors = [];

        foreach ($this->getResponses() as $response) {
            foreach ($response->getErrors() as $error) {
                $errors[] = $error;
            }
        }

        return $errors;
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        foreach ($this->getResponses() as $response) {
            if ($response->hasErrors()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->getStatusCode() < 400;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        if ($this->statusCode !== null) {
            return $this->statusCode;
        }

        foreach ($this->getResponses() as $response) {
            if ($response->hasErrors()) {
                return static::STATUS_CODE_ERROR;
            }
        }

        return static::STATUS_CODE_SUCCESS;
    }

    /**
     * @param int $code
     * @return ResponseInterface|$this
     */
    public function withStatusCode(int $code): ResponseInterface
    {
        $this->statusCode = $code;

        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $result = [];

        foreach ($this->getResponses() as $response) {
            $result[] = $response->getData();
        }

        return $result;
    }

    /**
     * @param array|null $data
     * @return ResponseInterface
     * @throws \LogicException
     */
    public function withData(?array $data): ResponseInterface
    {
        throw new \LogicException('Can not update data. The ' . __CLASS__ . ' is immutable.');
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $result = [];

        foreach ($this->getResponses() as $response) {
            $result[] = $response->toArray();
        }

        return $result;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->render();
    }
}
