<?php

declare(strict_types=1);

namespace Railt\Http\Factory;

use Psr\Http\Message\ResponseFactoryInterface as PsrResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Railt\Contracts\Http\ErrorInterface;
use Railt\Contracts\Http\Error\LocationInterface;
use Railt\Contracts\Http\Factory\ResponseSerializerInterface;
use Railt\Contracts\Http\Response\ExtensionInterface;
use Railt\Contracts\Http\ResponseInterface;
use Railt\Http\Factory\Exception\SerializingException;

final class GraphQLResponseSerializer implements ResponseSerializerInterface
{
    /**
     * @param LocationInterface $location
     * @return array{
     *     line: int<1, max>,
     *     column: int<1, max>
     * }
     */
    private function locationToArray(LocationInterface $location): array
    {
        return [
            'line' => $location->getLine(),
            'column' => $location->getColumn(),
        ];
    }

    /**
     * @param iterable<LocationInterface> $locations
     * @return list<array{
     *     line: int<1, max>,
     *     column: int<1, max>
     * }>
     */
    private function locationsToArray(iterable $locations): array
    {
        $result = [];

        foreach ($locations as $location) {
            $result[] = $this->locationToArray($location);
        }

        return $result;
    }

    /**
     * @param iterable<non-empty-string|int<0, max>> $path
     * @return list<non-empty-string|int<0, max>>
     */
    private function pathToArray(iterable $path): array
    {
        if ($path instanceof \Traversable) {
            return \iterator_to_array($path, false);
        }

        return \array_values($path);
    }

    /**
     * @param iterable<non-empty-string, ExtensionInterface> $extensions
     * @return array<non-empty-string, mixed>
     */
    private function extensionsToArray(iterable $extensions): array
    {
        $result = [];

        foreach ($extensions as $name => $extension) {
            $result[$name] = $extension;
        }

        return $result;
    }

    /**
     * @param ErrorInterface $error
     *
     * @return array{
     *     message: string,
     *     locations?: list<array{
     *         line: int<1, max>,
     *         column: int<1, max>
     *     }>,
     *     path?: list<non-empty-string|int<0, max>>,
     *     extensions?: array<non-empty-string, mixed>
     * }
     *
     * @psalm-suppress all
     */
    private function errorToArray(ErrorInterface $error): array
    {
        return [
            'message' => $error->getMessage(),
            ...\array_filter([
                'locations' => $this->locationsToArray(
                    $error->getLocations(),
                ),
                'path' => $this->pathToArray(
                    $error->getPath(),
                ),
                'extensions' => $this->extensionsToArray(
                    $error->getExtensions(),
                ),
            ])
        ];
    }

    /**
     * @param iterable<ErrorInterface> $errors
     *
     * @return list<array{
     *     message: string,
     *     locations?: list<array{
     *         line: int<1, max>,
     *         column: int<1, max>
     *     }>,
     *     path?: list<non-empty-string|int<0, max>>,
     *     extensions?: array<non-empty-string, mixed>
     * }>
     */
    private function errorsToArray(iterable $errors): array
    {
        $result = [];

        foreach ($errors as $error) {
            $result[] = $this->errorToArray($error);
        }

        return $result;
    }

    public function toArray(ResponseInterface $response): array
    {
        return \array_filter([
            'errors' => $this->errorsToArray($response->getErrors()),
            'data' => $response->getData(),
        ]);
    }

    public function toJson(ResponseInterface $response, int $json = self::DEFAULT_JSON_FLAGS): string
    {
        $array = $this->toArray($response);

        try {
            return \json_encode($array, $json | \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw SerializingException::fromJsonException($e);
        }
    }

    public function toResponse(
        PsrResponseFactoryInterface $factory,
        ResponseInterface $response,
        int $json = self::DEFAULT_JSON_FLAGS,
    ): PsrResponseInterface {
        [$code, $reason] = $this->getResponseStatus($response);

        $psr = $factory->createResponse($code, $reason);

        foreach ($this->getResponseHeaders() as $name => $value) {
            $psr = $psr->withAddedHeader($name, $value);
        }

        $content = $this->toJson($response, $json);

        $body = $psr->getBody();
        $body->rewind();
        $body->write($content);
        $body->rewind();

        return $psr;
    }

    /**
     * @return array<non-empty-string, non-empty-string>
     */
    private function getResponseHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * @return array{int<1, max>, non-empty-string}
     */
    private function getResponseStatus(ResponseInterface $response): array
    {
        if ($response->isSuccessful()) {
            return [200, 'OK'];
        }

        return [500, 'Internal Server Error'];
    }
}
