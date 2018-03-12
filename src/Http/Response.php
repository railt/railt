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
     * Default error message.
     */
    private const SERVER_ERROR_MESSAGE = 'Internal Server Error';

    /**
     * Data field name
     */
    public const FIELD_DATA = 'data';

    /**
     * Errors field name
     */
    public const FIELD_ERRORS = 'errors';

    /**
     * @var array
     */
    private $data;

    /**
     * @var array|\Throwable[]
     */
    private $errors;

    /**
     * @var bool
     */
    private $debug = false;

    /**
     * @var int
     */
    private $statusCode;

    /**
     * Response constructor.
     * @param array $data
     * @param array|\Throwable[] $errors
     */
    public function __construct(array $data = [], array $errors = [])
    {
        $this->data   = $data;
        $this->errors = $errors;

        $this->statusCode = $this->isSuccessful() ? 200 : 500;
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
        return \count($this->errors) > 0;
    }

    /**
     * @param \Throwable[] ...$errors
     * @return Response|static
     */
    public static function error(\Throwable ...$errors): self
    {
        return new static([], $errors);
    }

    /**
     * @param int $code
     * @return ResponseInterface
     */
    public function withStatusCode(int $code): ResponseInterface
    {
        $this->statusCode = $code;

        return $this;
    }

    /**
     * @param bool $enabled
     * @return $this|Response
     */
    public function debug(bool $enabled): self
    {
        $this->debug = $enabled;

        return $this;
    }

    /**
     * @param \Throwable $error
     * @return Response
     */
    public function withError(\Throwable $error): self
    {
        $this->errors[] = $error;

        return $this;
    }

    /**
     * @param array $data
     * @return Response
     */
    public function with(array $data): self
    {
        $this->data = \array_merge($this->data, $data);

        return $this;
    }

    /**
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function send(): void
    {
        if (\headers_sent()) {
            $error = 'Method %s() are not allowed. Headers already sent. Use %s() otherwise.';
            throw new \RuntimeException(\sprintf($error, __METHOD__, 'render'));
        }

        \http_response_code($this->getStatusCode());
        \header('Content-Type: application/json');

        echo $this->render();

        \flush();
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return string
     * @throws \LogicException
     */
    public function render(): string
    {
        $options = \JSON_HEX_TAG | \JSON_HEX_APOS | \JSON_HEX_AMP | \JSON_HEX_QUOT;

        if ($this->debug) {
            $options |= \JSON_PRETTY_PRINT;
        }

        return \json_encode($this->toArray(), $options);
    }

    /**
     * @return array
     * @throws \LogicException
     */
    public function toArray(): array
    {
        return [
            static::FIELD_DATA   => $this->getData(),
            static::FIELD_ERRORS => $this->getErrors(),
        ];
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return array[]
     */
    public function getErrors(): array
    {
        $result = [];

        foreach ($this->errors as $error) {
            foreach ($this->errorsToArray($error) as $sub) {
                $result[] = $sub;
            }
        }

        return $result;
    }

    /**
     * @param \Throwable $error
     * @return array
     */
    private function errorsToArray(\Throwable $error): array
    {
        $result = [];

        do {
            $result[] = $this->errorToArray($error);
        } while ($error = $error->getPrevious());

        return $result;
    }

    /**
     * @param \Throwable $error
     * @return array
     */
    private function errorToArray(\Throwable $error): array
    {
        $result = ['message' => $this->getErrorMessage($error)];

        if ($error instanceof GraphQLException) {
            $result['locations'] = $this->getErrorLocations($error);
            $result['path']      = $error->getPath();
        }

        if ($this->debug) {
            $result['in'] = $error->getFile() . ':' . $error->getLine();
            $result['trace'] = \explode("\n", $error->getTraceAsString());
        }

        return $result;
    }

    /**
     * @param \Throwable $error
     * @return string
     */
    private function getErrorMessage(\Throwable $error): string
    {
        if ($error instanceof GraphQLException || $this->debug) {
            return $error->getMessage();
        }

        return self::SERVER_ERROR_MESSAGE;
    }

    /**
     * @param GraphQLException $error
     * @return array
     */
    private function getErrorLocations(GraphQLException $error): array
    {
        $result = [];

        foreach ($error->getLocations() as $location) {
            $result[] = [
                'line'   => $location->getLine(),
                'column' => $location->getColumn(),
            ];
        }

        return $result;
    }

    /**
     * @return array|\Throwable[]
     */
    public function getExceptions(): iterable
    {
        return $this->errors;
    }
}
