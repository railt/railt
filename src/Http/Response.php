<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

/**
 * Class Response
 */
class Response implements ResponseInterface
{
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
     * @param array $errors
     */
    public function __construct(array $data = [], array $errors = [])
    {
        if (\count($data) || \count($errors)) {
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
     * @return iterable
     */
    public function getExceptions(): iterable
    {
        foreach ($this->messages as $message) {
            yield from $message->getExceptions();
        }
    }

    /**
     * @return void
     */
    public function send(): void
    {
        if (! \headers_sent()) {
            \http_response_code($this->getStatusCode());
            \header('Content-Type: application/json');
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
        return \json_decode(\json_encode($this->getMessages()), true);
    }

    /**
     * @return array
     */
    private function getMessages(): array
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
}
