<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Support\Debuggable;

/**
 * Class Response
 * @package Railt\Http
 */
class Response implements ResponseInterface
{
    use Debuggable;

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
    private $data = [];

    /**
     * @var array
     */
    private $errors = [];

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
     * @param \Throwable[] ...$errors
     * @return Response|static
     */
    public static function error(\Throwable ...$errors): Response
    {
        return new static([], $errors);
    }

    /**
     * @param \Throwable $error
     * @return Response
     */
    public function withError(\Throwable $error): Response
    {
        $this->errors[] = $error;

        return $this;
    }

    /**
     * @param array $data
     * @return Response
     */
    public function with(array $data): Response
    {
        $this->data = array_merge($this->data, $data);

        return $this;
    }

    /**
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function send(): void
    {
        if (headers_sent()) {
            throw new \RuntimeException('Method send() are not allowed. Headers already sent');
        }

        header('Content-Type: application/json');
        echo $this->render();
    }

    /**
     * @return string
     * @throws \LogicException
     */
    public function render(): string
    {
        $options = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT;

        if ($this->debug) {
            $options |= JSON_PRETTY_PRINT;
        }

        return json_encode($this->toArray(), $options);
    }

    /**
     * @return array
     * @throws \LogicException
     */
    public function toArray(): array
    {
        $errors = $this->getErrors();

        return [
            static::FIELD_DATA   => $this->getData(),
            static::FIELD_ERRORS => $errors instanceof \Traversable ? iterator_to_array($errors) : $errors,
        ];
    }

    /**
     * @return iterable|array[]
     */
    public function getErrors(): iterable
    {
        foreach ($this->errors as $error) {
            yield $error instanceof \Throwable
                ? ErrorFormatter::render($error, $this->debug)
                : $error;
        }
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
