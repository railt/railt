<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Class Response
 */
class Response implements ResponseInterface
{
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
     * @var array
     */
    private $errors;

    /**
     * @var bool
     */
    private $debug = false;

    /**
     * Response constructor.
     * @param array $data
     * @param array|\Throwable[] $errors
     */
    public function __construct(array $data = [], array $errors = [])
    {
        $this->data   = $data;
        $this->errors = $errors;
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
     * @param \Throwable[] ...$errors
     * @return Response|static
     */
    public static function error(\Throwable ...$errors): self
    {
        return new static([], $errors);
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

        \header('Content-Type: application/json');

        echo $this->render();

        \flush();
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
     * @return array[]
     */
    public function getErrors(): array
    {
        $result = [];

        foreach ($this->errors as $error) {
            $result[] = $this->formatError($error, $this->debug);
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getNativeErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param mixed $error
     * @param bool $debug
     * @return array
     */
    private function formatError($error, bool $debug = false): array
    {
        switch (true) {
            case $error instanceof Arrayable:
                return $error->toArray();

            case $error instanceof \Throwable:
                return ErrorFormatter::render($error, $debug);

            case $error instanceof \JsonSerializable:
                return $error->jsonSerialize();
        }

        return (array)$error;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return \count($this->errors) > 0;
    }

    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return ! $this->hasErrors();
    }
}
