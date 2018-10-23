<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Http\Exception\GraphQLException;

/**
 * Class Response
 */
class Response implements ResponseInterface
{
    /**
     * @var bool
     */
    private $vendor = true;

    /**
     * @var bool
     */
    private $debug = false;

    /**
     * @var array|MessageInterface[]
     */
    private $messages = [];

    /**
     * @var int
     */
    private $statusCode;

    /**
     * Response constructor.
     * @param array $data
     * @param iterable|\Throwable[] $errors
     */
    public function __construct(array $data = [], iterable $errors = [])
    {
        if ($data || $errors) {
            $this->addMessage(new Message($data, $errors));
        }
    }

    /**
     * @param bool $debug
     * @return ResponseInterface
     */
    public function debug(bool $debug = false): ResponseInterface
    {
        $this->debug = $debug;

        return $this;
    }

    /**
     * @param MessageInterface $message
     * @return ResponseInterface
     */
    public function addMessage(MessageInterface $message): ResponseInterface
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * @return iterable|\Throwable[]
     */
    public function getExceptions(): iterable
    {
        foreach ($this->messages as $message) {
            yield from $message->getExceptions();
        }
    }

    /**
     * @return iterable|MessageInterface[]
     */
    public function getMessages(): iterable
    {
        return $this->messages;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $result = [];

        foreach ($this->messages as $message) {
            /** @noinspection SlowArrayOperationsInLoopInspection */
            $result = \array_merge_recursive($result, $message->getData());
        }

        return $result;
    }

    /**
     * @param bool $enable
     */
    public function withVendorHeader(bool $enable = true): void
    {
        $this->vendor = $enable;
    }

    /**
     * @return void
     */
    public function send(): void
    {
        if (! \headers_sent()) {
            \http_response_code($this->getStatusCode());
            \header('Content-Type: application/json');

            if ($this->vendor) {
                \header('X-GraphQL-Server: Railt');
            }
        }

        echo $this->render();

        \flush();
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        if ($this->statusCode === null) {
            $this->statusCode = $this->hasErrors()
                ? static::STATUS_CODE_ERROR
                : static::STATUS_CODE_SUCCESS;
        }

        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $options = \JSON_HEX_TAG | \JSON_HEX_APOS | \JSON_HEX_AMP | \JSON_HEX_QUOT | \JSON_PARTIAL_OUTPUT_ON_ERROR;

        if ($this->debug) {
            $options |= \JSON_PRETTY_PRINT;
        }

        return \json_encode($this->toArray(), $options);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return \json_decode(\json_encode($this->getMessagesAsArray()), true);
    }

    /**
     * @return array
     */
    private function getMessagesAsArray(): array
    {
        switch (\count($this->messages)) {
            case 0:
                return (new Message())->toArray();

            case 1:
                return \reset($this->messages)->toArray();

            default:
                $result = [];

                foreach ($this->messages as $message) {
                    $result[] = $message->toArray();
                }

                return $result;
        }
    }

    /**
     * @return bool
     */
    public function isBatched(): bool
    {
        return \count($this->messages) > 1;
    }

    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return ! $this->hasErrors();
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        foreach ($this->messages as $message) {
            if ($message->hasErrors()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @param array $response
     * @return ResponseInterface
     */
    public static function fromArray(array $response): ResponseInterface
    {
        $errors = [];

        foreach ($response[static::FIELD_ERRORS] ?? [] as $error) {
            $errors[] = GraphQLException::fromArray((array)$error);
        }

        return new static((array)($response[static::FIELD_DATA] ?? []), $errors);
    }
}
