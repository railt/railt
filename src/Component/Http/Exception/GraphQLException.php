<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Http\Exception;

use Railt\Component\Exception\ExternalException;
use Railt\Component\Http\Extension\DataExtension;
use Railt\Component\Http\Extension\HasExtensions;
use Railt\Component\Http\Exception\GraphQLExceptionLocation as Location;

/**
 * Class GraphQLException
 */
class GraphQLException extends ExternalException implements GraphQLExceptionInterface
{
    use HasExtensions;

    /**
     * For all errors that reflect the internal state of the application
     * and should not be visible to users, the message should be replaced
     * with this message.
     *
     * @var string
     */
    public const INTERNAL_EXCEPTION_MESSAGE = 'Internal Server Error';

    /**
     * @var string
     */
    public const UNDEFINED_EXCEPTION_MESSAGE = 'Unexpected GraphQL Exception';

    /**
     * @var array|int[]|string[]
     */
    protected $path = [];

    /**
     * @var array|GraphQLExceptionLocationInterface[]
     */
    protected $locations = [];

    /**
     * @var bool
     */
    protected $public = false;

    /**
     * @var string
     */
    private $originalMessage;

    /**
     * GraphQLException constructor.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        $this->originalMessage = $message;

        parent::__construct(static::INTERNAL_EXCEPTION_MESSAGE, $code, $previous);
    }

    /**
     * @param array $error
     * @return GraphQLException
     */
    public static function fromArray(array $error): self
    {
        $message = $error[static::FIELD_MESSAGE] ?? static::UNDEFINED_EXCEPTION_MESSAGE;

        $exception = (new static($message))->publish();

        foreach ($error[static::FIELD_LOCATIONS] ?? [] as $location) {
            $exception->addLocation(Location::fromArray($location));
        }

        foreach ($error[static::FIELD_PATH] ?? [] as $chunk) {
            $exception->addPath($chunk);
        }

        foreach ($error[static::FIELD_EXTENSIONS] ?? [] as $name => $value) {
            $exception->withExtension($name, new DataExtension($value));
        }

        return $exception;
    }

    /**
     * @return GraphQLExceptionInterface|$this
     */
    public function publish(): GraphQLExceptionInterface
    {
        $this->public = true;
        $this->message = $this->originalMessage;

        return $this;
    }

    /**
     * @param GraphQLExceptionLocationInterface $location
     * @return GraphQLException|$this
     */
    public function addLocation(GraphQLExceptionLocationInterface $location): self
    {
        $this->locations[] = $location;

        return $this;
    }

    /**
     * @param int|string $chunk
     * @return GraphQLException|$this
     */
    public function addPath($chunk): self
    {
        \assert(\is_string($chunk) || \is_int($chunk), 'Path chunk should be an int or string');

        $this->path[] = $chunk;

        return $this;
    }

    /**
     * @param \Throwable $throwable
     * @return GraphQLExceptionInterface|self
     */
    public static function fromThrowable(\Throwable $throwable): GraphQLExceptionInterface
    {
        if ($throwable instanceof GraphQLExceptionInterface) {
            return $throwable;
        }

        $root = static::getRootException($throwable);

        $exception = new static($throwable->getMessage(), $throwable->getCode(), $root);
        $exception->from($throwable);

        return $exception;
    }

    /**
     * @param \Throwable $throwable
     * @return \Throwable
     */
    public static function getRootException(\Throwable $throwable): \Throwable
    {
        while ($throwable->getPrevious()) {
            $throwable = $throwable->getPrevious();
        }

        return $throwable;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return \array_filter([
            static::FIELD_MESSAGE    => $this->getMessage(),
            static::FIELD_LOCATIONS  => $this->getLocations()
                ?: null,
            static::FIELD_PATH       => $this->getPath()
                ?: null,
            static::FIELD_EXTENSIONS => $this->getExtensions()
                ?: null,
        ]);
    }

    /**
     * @return iterable|GraphQLExceptionInterface[]
     */
    public function getLocations(): iterable
    {
        return $this->locations;
    }

    /**
     * @param iterable|GraphQLExceptionLocationInterface[] $locations
     * @return GraphQLException
     */
    public function setLocations(iterable $locations): self
    {
        $this->locations = [];

        foreach ($locations as $location) {
            $this->addLocation($location);
        }

        return $this;
    }

    /**
     * @return iterable|string[]|int[]
     */
    public function getPath(): iterable
    {
        return \array_filter($this->path, function ($path): bool {
            // Path chunk should be scalar
            $isScalar = \is_string($path) || \is_int($path);

            // Or object which casts to string
            $isStringable = \is_object($path) && \method_exists($path, '__toString');

            return $isScalar || $isStringable;
        });
    }

    /**
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->public;
    }

    /**
     * @return GraphQLExceptionInterface|$this
     */
    public function hide(): GraphQLExceptionInterface
    {
        $this->public = false;
        $this->message = static::INTERNAL_EXCEPTION_MESSAGE;

        return $this;
    }

    /**
     * @param iterable|string[]|int[] $chunks
     * @return GraphQLException|$this
     */
    public function setPaths(iterable $chunks): self
    {
        $this->path = [];

        foreach ($chunks as $chunk) {
            $this->addPath($chunk);
        }

        return $this;
    }
}
