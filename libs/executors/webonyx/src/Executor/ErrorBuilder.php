<?php

declare(strict_types=1);

namespace Railt\Executor\Webonyx\Executor;

use GraphQL\Error\Error;
use GraphQL\Error\InvariantViolation;
use Railt\Contracts\Http\Error\CategoryInterface;
use Railt\Contracts\Http\Error\LocationInterface;
use Railt\Contracts\Http\ErrorInterface;
use Railt\Contracts\Http\Factory\ErrorFactoryInterface;
use Railt\Contracts\Http\Response\ExtensionInterface;
use Railt\Http\Factory\GraphQLErrorFactory;

final class ErrorBuilder
{
    public function __construct(
        private readonly ErrorFactoryInterface $errors = new GraphQLErrorFactory(),
    ) {
    }

    public function create(\Throwable $e): ErrorInterface
    {
        if ($e instanceof Error) {
            return $this->createErrorFromWebonyx($e)
                ->withCategory($this->createCategoryFromWebonyx($e))
                ->withLocations($this->createLocationsFromWebonyx($e))
                ->withPath($this->createPathFromWebonyx($e))
                ->withExtensions($this->createExtensionsFromWebonyx($e))
            ;
        }

        return $this->errors->createError($e->getMessage(), (int)$e->getCode(), $e)
            ->withCategory($this->errors->createInternalErrorCategory())
        ;
    }

    /**
     * @return iterable<non-empty-string|int<0, max>>
     *
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     */
    private function createPathFromWebonyx(Error $error): iterable
    {
        return (array)$error->getPath();
    }

    /**
     * @psalm-suppress ArgumentTypeCoercion
     *
     * @return iterable<LocationInterface>
     */
    private function createLocationsFromWebonyx(Error $error): iterable
    {
        foreach ($error->getLocations() as $location) {
            yield $this->errors->createErrorLocation(
                $location->line,
                $location->column,
            );
        }
    }

    /**
     * @return iterable<ExtensionInterface>
     */
    private function createExtensionsFromWebonyx(Error $error): iterable
    {
        /** @var array|null $extensions */
        $extensions = $error->getExtensions();

        if ($extensions === null) {
            return;
        }

        /** @psalm-suppress MixedAssignment */
        foreach ($extensions as $key => $value) {
            if (!\is_string($key) || $key === '') {
                continue;
            }

            yield new Extension($key, $value);
        }
    }

    private function createCategoryFromWebonyx(Error $error): CategoryInterface
    {
        return $error->isClientSafe()
            ? $this->errors->createClientErrorCategory()
            : $this->errors->createInternalErrorCategory()
        ;
    }

    /**
     * @psalm-suppress PossiblyNullReference : Exception cannot be null
     */
    private function createErrorFromWebonyx(Error $error): ErrorInterface
    {
        $exception = $error;

        while ($exception?->getPrevious() !== null) {
            if ($exception instanceof ErrorInterface) {
                break;
            }

            $exception = $exception->getPrevious();
        }

        if ($exception instanceof ErrorInterface) {
            return $exception;
        }

        if ($exception instanceof InvariantViolation) {
            return $this->errors->createError('Schema Error: ' . $exception->getMessage());
        }

        return $this->errors->createError($exception->getMessage());
    }
}
