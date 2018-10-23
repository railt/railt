<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Exception;

use Railt\Http\Exception\Extension\DataExtension;
use Railt\Http\Exception\Extension\ExtensionInterface;
use Railt\Http\Exception\GraphQLExceptionLocation as Location;

/**
 * Class GraphQLException
 */
class GraphQLException extends \LogicException implements GraphQLExceptionInterface
{
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
     * @var array|ExtensionInterface[]
     */
    protected $extensions = [];

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
        $message = $error[static::JSON_MESSAGE_KEY] ?? static::UNDEFINED_EXCEPTION_MESSAGE;

        $exception = (new static($message))->makePublic();

        foreach ($error[static::JSON_LOCATIONS_KEY] ?? [] as $location) {
            $exception->addLocation(Location::fromArray($location));
        }

        foreach ($error[static::JSON_PATH_KEY] ?? [] as $chunk) {
            $exception->addPath($chunk);
        }

        foreach ($error[static::JSON_EXTENSIONS_KEY] ?? [] as $name => $value) {
            $exception->addExtension($name, new DataExtension($value));
        }

        return $exception;
    }

    /**
     * @return $this|GraphQLException
     */
    public function makePublic(): self
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
            static::JSON_MESSAGE_KEY    => $this->getPublicMessage(),
            static::JSON_LOCATIONS_KEY  => $this->getLocations() ?: null,
            static::JSON_PATH_KEY       => $this->getPath() ?: null,
            static::JSON_EXTENSIONS_KEY => \array_map(function (ExtensionInterface $ext) {
                return $ext->getValue();
            }, $this->getExtensions()) ?: null,
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
     * @return iterable
     */
    public function getExtensions(): iterable
    {
        return $this->extensions;
    }

    /**
     * @param iterable|ExtensionInterface[] $extensions
     * @return $this|GraphQLException
     */
    public function setExtensions(iterable $extensions): self
    {
        $this->extensions = [];

        foreach ($extensions as $key => $value) {
            $this->addExtension($key, $value);
        }

        return $this;
    }

    /**
     * @param string|int|bool|float $key
     * @param ExtensionInterface $value
     * @return $this|GraphQLException
     */
    public function addExtension($key, ExtensionInterface $value): self
    {
        \assert(\is_scalar($key) && $key, 'Extension key should be a non-null scalar');

        $this->extensions[$key] = $value;

        return $this;
    }

    /**
     * @param mixed $key
     * @return null|ExtensionInterface
     */
    public function getExtension($key): ?ExtensionInterface
    {
        return $this->extensions[$key] ?? null;
    }

    /**
     * @return $this|GraphQLException
     */
    public function makePrivate(): self
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
