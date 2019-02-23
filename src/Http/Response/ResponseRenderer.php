<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Response;

use Railt\Http\Exception\GraphQLExceptionInterface;
use Railt\Http\ResponseInterface;
use Railt\Json\Json;

/**
 * Trait ResponseRenderer
 *
 * @mixin Renderable
 */
trait ResponseRenderer
{
    /**
     * @return array
     */
    abstract public function toArray(): array;

    /**
     * @return int
     */
    abstract public function getStatusCode(): int;

    /**
     * @var int
     */
    protected $options = \JSON_HEX_TAG
        | \JSON_HEX_APOS
        | \JSON_HEX_AMP
        | \JSON_HEX_QUOT
        | \JSON_PARTIAL_OUTPUT_ON_ERROR;

    /**
     * @param int|null $jsonOptions
     * @return string
     * @throws \Railt\Json\Exception\JsonException
     */
    public function render(int $jsonOptions = null): string
    {
        return Json::encoder()
            ->setOptions($jsonOptions ?? $this->options)
            ->encode((object)$this->toArray());
    }

    /**
     * @param int $options
     * @return Renderable
     */
    public function withJsonOptions(int $options): Renderable
    {
        $this->options |= $options;

        return $this;
    }

    /**
     * @param int $options
     * @return Renderable
     */
    public function setJsonOptions(int $options): Renderable
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return int
     */
    public function getJsonOptions(): int
    {
        return $this->options;
    }

    /**
     * @return void
     * @throws \Railt\Json\Exception\JsonException
     */
    public function send(): void
    {
        if (! \headers_sent()) {
            \http_response_code($this->getStatusCode());
            \header('Content-Type: application/json');
            \header('X-GraphQL-Server: Railt');
        }

        echo $this->render();

        \flush();
    }

    /**
     * @return object|mixed
     */
    public function jsonSerialize()
    {
        return (object)$this->toArray();
    }

    /**
     * @return string
     * @throws \Railt\Json\Exception\JsonException
     */
    public function __toString(): string
    {
        try {
            return $this->render();
        } catch (\JsonException $e) {
            // We should not use the `toArray()` method,
            // because it may throw similar exceptions.
            return Json::encoder()
                ->setOptions($this->getJsonOptions())
                ->encode((object)[
                    ResponseInterface::FIELD_ERRORS => [
                        [GraphQLExceptionInterface::FIELD_MESSAGE => 'Fatal JSON encoding error'],
                    ],
                ]);
        }
    }
}
