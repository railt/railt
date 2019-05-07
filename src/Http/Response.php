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
use Railt\Http\Exception\GraphQLExceptionLocation;
use Railt\Http\Extension\HasExtensions;
use Railt\Http\Response\HasExceptions;
use Railt\Http\Response\ResponseRenderer;

/**
 * Class Response
 */
class Response implements ResponseInterface
{
    use ResponseRenderer;
    use HasExtensions;
    use HasExceptions;

    /**
     * @var int|null
     */
    protected $statusCode;

    /**
     * @var array|null
     */
    protected $data;

    /**
     * Response constructor.
     *
     * @param array|null $data
     */
    public function __construct(array $data = null)
    {
        $this->data = $data;
    }

    /**
     * @return ResponseInterface|$this|self|static
     */
    public static function empty(): ResponseInterface
    {
        $response = new static();

        $exception = new GraphQLException('GraphQL request must contain a valid query data, but it came empty');
        $exception->addLocation(new GraphQLExceptionLocation(0, 0));
        $exception->publish();

        $response->withException($exception);

        return $response;
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
        if ($this->statusCode === null) {
            return $this->hasErrors() ? static::STATUS_CODE_ERROR : static::STATUS_CODE_SUCCESS;
        }

        return $this->statusCode;
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
     * @param array|null $data
     * @return ResponseInterface|$this
     */
    public function withData(?array $data): ResponseInterface
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return \array_filter([
            static::FIELD_ERRORS     => $this->getErrors() ?: null,
            static::FIELD_DATA       => $this->getData(),
            static::FIELD_EXTENSIONS => $this->getExtensions() ?: null,
        ]);
    }

    /**
     * @return array|null
     */
    public function getData(): ?array
    {
        return $this->data;
    }
}
