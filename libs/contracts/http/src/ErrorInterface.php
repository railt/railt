<?php

declare(strict_types=1);

namespace Railt\Contracts\Http;

use Railt\Contracts\Http\Error\CategoryInterface;
use Railt\Contracts\Http\Error\LocationInterface;
use Railt\Contracts\Http\Response\ExtensionInterface;

interface ErrorInterface extends \Throwable
{
    /**
     * Returns list of associations to a particular field in the GraphQL result.
     *
     * It MUST contain an entry with the key `path` that details the path of
     * the response field which experienced the error. This allows clients to
     * identify whether an empty result is intentional or caused by a
     * runtime error.
     *
     * @return iterable<non-empty-string|int<0, max>>
     */
    public function getPath(): iterable;

    /**
     * Returns new instance of {@see ErrorInterface} with the passed
     * path item argument.
     *
     * @psalm-immutable This method MUST retain the state of the current
     *                  instance, and return an instance that contains the
     *                  specified path item.
     *
     * @param non-empty-string|int<0, max> $item
     */
    public function withAddedPath(string|int $item): self;

    /**
     * Returns new instance of {@see ErrorInterface} with the passed
     * path items.
     *
     * @psalm-immutable This method MUST retain the state of the current
     *                  instance, and return an instance that contains the
     *                  specified path items.
     *
     * @param iterable<non-empty-string|int<0, max>> $path
     */
    public function withPath(iterable $path): self;

    /**
     * Returns list of associations to a particular point
     * ({@see LocationInterface}) in the requested GraphQL document.
     *
     * @return iterable<LocationInterface>
     */
    public function getLocations(): iterable;

    /**
     * Returns new instance of {@see ErrorInterface} with the passed
     * location item.
     *
     * @psalm-immutable This method MUST retain the state of the current
     *                  instance, and return an instance that contains the
     *                  specified location item.
     */
    public function withAddedLocation(LocationInterface $location): self;

    /**
     * Returns new instance of {@see ErrorInterface} with the passed
     * location items.
     *
     * @param iterable<LocationInterface> $locations
     *
     * @psalm-immutable This method MUST retain the state of the current
     *                  instance, and return an instance that contains the
     *                  specified location items.
     */
    public function withLocations(iterable $locations): self;

    /**
     * Returns list of implementors to extend the protocol however they see
     * fit, and hence there are no additional restrictions on its contents.
     *
     * @return iterable<non-empty-string, ExtensionInterface>
     */
    public function getExtensions(): iterable;

    /**
     * Returns new instance of {@see ErrorInterface} with the passed
     * extension item.
     *
     * @psalm-immutable This method MUST retain the state of the current
     *                  instance, and return an instance that contains the
     *                  specified extension item.
     *
     * @param non-empty-string|null $name In case of this argument is {@see null}
     *        then the value of the {@see ExtensionInterface::getName()} method
     *        will be used as the default extension's field name.
     */
    public function withAddedExtension(ExtensionInterface $extension, string $name = null): self;

    /**
     * Returns new instance of {@see ErrorInterface} with the passed
     * extension items.
     *
     * @psalm-immutable This method MUST retain the state of the current
     *                  instance, and return an instance that contains the
     *                  specified extension items.
     *
     * @param iterable<mixed, ExtensionInterface> $extensions
     *        In case of the key of iterable is not non-empty {@see string}
     *        then the value of the {@see ExtensionInterface::getName()} method
     *        will be used as the default extension's field name.
     */
    public function withExtensions(iterable $extensions): self;

    /**
     * Returns category of the exception.
     */
    public function getCategory(): CategoryInterface;

    /**
     * Returns new instance of {@see ErrorInterface} with the passed
     * category item.
     *
     * @psalm-immutable This method MUST retain the state of the current
     *                  instance, and return an instance that contains the
     *                  specified category.
     */
    public function withCategory(CategoryInterface $category): self;

    /**
     * @return array{
     *  message: string,
     *  locations?: list<array{line: int<1, max>, column: int<1, max>}>,
     *  path?: list<non-empty-string|int>,
     *  extensions?: array<non-empty-string, mixed>
     * }
     */
    public function toArray(): array;
}
