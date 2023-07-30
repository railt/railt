<?php

declare(strict_types=1);

namespace Railt\Http;

use Railt\Contracts\Http\ErrorInterface;
use Railt\Contracts\Http\Error\CategoryInterface;
use Railt\Contracts\Http\Error\LocationInterface;
use Railt\Contracts\Http\Response\ExtensionInterface;
use Railt\Http\Exception\Category;

class GraphQLError extends \Exception implements ErrorInterface
{
    /**
     * For all errors that reflect the internal state of the application
     * and should not be visible to users, the message should be replaced
     * with this message.
     *
     * @var non-empty-string
     */
    public const INTERNAL_EXCEPTION_MESSAGE = 'Internal Server Error';

    /**
     * If an error can be associated to a particular field in the GraphQL
     * result, it MUST contain an entry with the key `path` that details the
     * path of the response field which experienced the error. This allows
     * clients to identify whether an empty (empty {@see array}) result is
     * intentional or caused by a runtime error.
     *
     * @var list<non-empty-string|int<0, max>>
     */
    protected array $path = [];

    /**
     * If an error can be associated to a particular point in the requested
     * GraphQL document, it should contain a list of locations.
     *
     * @var list<LocationInterface>
     */
    protected array $locations = [];

    /**
     * Reserved for implementors to extend the protocol however they see fit,
     * and hence there are no additional restrictions on its contents.
     *
     * @var array<non-empty-string, ExtensionInterface>
     */
    protected array $extensions = [];

    protected CategoryInterface $category = Category::INTERNAL;

    /**
     * An original short, human-readable summary of the
     * problem set by the user.
     */
    protected ?string $originalMessage;

    final public function __construct(
        string $message,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        $this->originalMessage = $message;

        parent::__construct($message, $code, $previous);

        $this->syncMessageWithCategory();
    }

    public static function fromException(\Throwable $e): self
    {
        return new static($e->getMessage(), (int)$e->getCode(), $e);
    }

    /**
     * @return list<non-empty-string|int<0, max>>
     */
    public function getPath(): array
    {
        return $this->path;
    }

    public function withAddedPath(int|string $item): self
    {
        $self = $this->clone();
        $self->addPath($item);

        return $self;
    }

    /**
     * Mutable equivalent of {@see ExceptionInterface::withAddedPath()} method.
     *
     * @link ExceptionInterface::withAddedPath() method description.
     *
     * @param non-empty-string|int<0, max> $path
     */
    public function addPath(string|int $path): void
    {
        $this->path[] = $path;
    }

    public function withPath(iterable $path): self
    {
        $self = $this->clone();
        $self->setPath($path);

        return $self;
    }

    /**
     * Mutable equivalent of {@see ExceptionInterface::withPath()} method.
     *
     * @link ExceptionInterface::withPath() method description.
     *
     * @param iterable<non-empty-string|int<0, max>> $path
     */
    public function setPath(iterable $path): void
    {
        if ($path instanceof \Traversable) {
            $path = \iterator_to_array($path, false);
        }

        $this->path = \array_values($path);
    }

    /**
     * @return list<LocationInterface>
     */
    public function getLocations(): array
    {
        return $this->locations;
    }

    public function withAddedLocation(LocationInterface $location): self
    {
        $self = $this->clone();
        $self->addLocation($location);

        return $self;
    }

    /**
     * Mutable equivalent of {@see ExceptionInterface::withAddedLocation()} method.
     *
     * @link ExceptionInterface::withAddedLocation() method description.
     */
    public function addLocation(LocationInterface $location): void
    {
        $this->locations[] = $location;
    }

    public function withLocations(iterable $locations): self
    {
        $self = $this->clone();
        $self->setLocations($locations);

        return $self;
    }

    /**
     * Mutable equivalent of {@see ExceptionInterface::withLocations()} method.
     *
     * @link ExceptionInterface::withLocations() method description.
     *
     * @param iterable<LocationInterface> $locations
     */
    public function setLocations(iterable $locations): void
    {
        if ($locations instanceof \Traversable) {
            $locations = \iterator_to_array($locations, false);
        }

        $this->locations = \array_values($locations);
    }

    /**
     * @return array<non-empty-string, ExtensionInterface>
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }

    public function withAddedExtension(ExtensionInterface $extension, string $name = null): self
    {
        $self = $this->clone();
        $self->addExtension($extension, $name);

        return $self;
    }

    /**
     * Mutable equivalent of {@see ExceptionInterface::withAddedExtension()} method.
     *
     * @link ExceptionInterface::withAddedExtension() method description.
     *
     * @param non-empty-string|null $name
     */
    public function addExtension(ExtensionInterface $extension, string $name = null): void
    {
        $this->extensions[$name ?: $extension->getName()] = $extension;
    }

    public function withExtensions(iterable $extensions): self
    {
        $self = $this->clone();
        $self->setExtensions($extensions);

        return $self;
    }

    /**
     * Mutable equivalent of {@see ExceptionInterface::withExtensions()} method.
     *
     * @link ExceptionInterface::withExtensions() method description.
     *
     * @param iterable<mixed, ExtensionInterface> $extensions
     *
     * @psalm-suppress MixedAssignment
     */
    public function setExtensions(iterable $extensions): void
    {
        foreach ($extensions as $key => $extension) {
            $key = \is_string($key) && $key !== '' ? $key : $extension->getName();

            $this->extensions[$key] = $extension;
        }
    }

    public function getCategory(): CategoryInterface
    {
        return $this->category;
    }

    public function withCategory(CategoryInterface $category): self
    {
        $self = $this->clone();
        $self->setCategory($category);

        return $self;
    }

    /**
     * Mutable equivalent of {@see ExceptionInterface::withCategory()} method.
     *
     * @link ExceptionInterface::withCategory() method description.
     */
    public function setCategory(CategoryInterface $category): void
    {
        $this->category = $category;

        $this->syncMessageWithCategory();
    }

    protected function syncMessageWithCategory(): void
    {
        /** @psalm-suppress PossiblyNullPropertyAssignmentValue */
        $this->message = $this->category->isClientSafe()
            ? $this->originalMessage
            : self::INTERNAL_EXCEPTION_MESSAGE
        ;
    }

    public function isClientSafe(): bool
    {
        return $this->category->isClientSafe();
    }

    protected function clone(): self
    {
        $instance = new static($this->message, $this->code, $this->getPrevious());

        $instance->file = $this->file;
        $instance->line = $this->line;

        $instance->message = $this->message;
        $instance->code = $this->code;
        $instance->path = $this->path;
        $instance->locations = $this->locations;
        $instance->extensions = $this->extensions;
        $instance->category = $this->category;
        $instance->originalMessage = $this->originalMessage;

        return $instance;
    }
}
