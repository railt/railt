<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Exception;

use Railt\Http\Exception\GraphQLExceptionLocation as Location;
use Railt\Http\Extension\DataExtension;
use Railt\Http\Extension\HasExtensions;

/**
 * Class GraphQLException
 */
class GraphQLException extends \LogicException implements GraphQLExceptionInterface
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
            $exception->addExtension($name, new DataExtension($value));
        }

        return $exception;
    }

    /**
     * @param \Throwable $exception
     * @return GraphQLException
     */
    public static function fromThrowable(\Throwable $exception): self
    {
        return new static($exception->getMessage(), $exception->getCode(), $exception);
    }

    /**
     * @return $this|GraphQLException
     */
    public function publish(): GraphQLExceptionInterface
    {
        $this->public = true;

        return $this;
    }

    /**
     * @param GraphQLExceptionLocationInterface $location
     * @return $this|GraphQLException
     */
    public function addLocation(GraphQLExceptionLocationInterface $location): self
    {
        $this->locations[] = $location;

        return $this;
    }

    /**
     * @param string|int $chunk
     * @return $this|GraphQLException
     */
    public function addPath($chunk): self
    {
        \assert(\is_string($chunk) || \is_int($chunk), 'Path chunk should be an int or string');

        $this->path[] = $chunk;

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return \array_filter([
            static::FIELD_MESSAGE    => $this->getPublicMessage(),
            static::FIELD_LOCATIONS  => $this->getLocations() ?: null,
            static::FIELD_PATH       => $this->getPath() ?: null,
            static::FIELD_EXTENSIONS => $this->getExtensions() ?: null,
        ]);
    }

    /**
     * @return string
     */
    public function getPublicMessage(): string
    {
        return $this->isPublic() ? $this->getMessage() : static::INTERNAL_EXCEPTION_MESSAGE;
    }

    /**
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->public;
    }

    /**
     * @return iterable|GraphQLExceptionLocationInterface[]
     */
    public function getLocations(): iterable
    {
        return $this->locations;
    }

    /**
     * @param iterable|GraphQLExceptionLocationInterface[] $locations
     * @return $this|GraphQLException
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
     * @return $this|GraphQLException
     */
    public function hide(): GraphQLExceptionInterface
    {
        $this->public = false;

        return $this;
    }

    /**
     * @param iterable|string[]|int[] $chunks
     * @return $this|GraphQLException
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
