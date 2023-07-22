<?php

declare(strict_types=1);

namespace Railt\Http;

use Railt\Contracts\Http\ErrorInterface;
use Railt\Contracts\Http\ResponseInterface;

class GraphQLResponse implements ResponseInterface
{
    /**
     * @var list<\Throwable>
     */
    protected array $exceptions = [];

    /**
     * Mapped exceptions.
     *
     * @var \WeakMap<\Throwable, ErrorInterface>
     */
    private readonly \WeakMap $map;

    /**
     * @param array|null $data
     * @param iterable<\Throwable> $exceptions
     */
    public function __construct(
        protected ?array $data = null,
        iterable $exceptions = [],
    ) {
        $this->map = new \WeakMap();

        $this->setExceptions($exceptions);
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function withData(array $data): self
    {
        $self = clone $this;
        $self->setData($data);

        return $self;
    }

    public function withoutData(): self
    {
        $self = clone $this;
        $self->removeData();

        return $self;
    }

    /**
     * Mutable equivalent of {@see DataProviderInterface::withData()} method.
     *
     * @link DataProviderInterface::withData() method description.
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * Mutable equivalent of {@see DataProviderInterface::withoutData()} method.
     *
     * @link DataProviderInterface::withoutData() method description.
     */
    public function removeData(): void
    {
        $this->data = null;
    }

    public function isSuccessful(): bool
    {
        return $this->exceptions === [];
    }

    public function getErrors(): iterable
    {
        foreach ($this->exceptions as $exception) {
            if ($exception instanceof ErrorInterface) {
                yield $exception;

                continue;
            }

            yield $this->map[$exception] ??= GraphQLError::fromException($exception);
        }
    }

    public function getExceptions(): iterable
    {
        return $this->exceptions;
    }

    /**
     * Mutable equivalent of {@see ExceptionProviderInterface::withExceptions()} method.
     *
     * @link ExceptionProviderInterface::withExceptions() method description.
     *
     * @param iterable<\Throwable> $exceptions
     */
    public function setExceptions(iterable $exceptions): void
    {
        foreach ($exceptions as $exception) {
            $this->addException($exception);
        }
    }

    /**
     * Mutable equivalent of {@see ExceptionProviderInterface::withAddedException()} method.
     *
     * @link ExceptionProviderInterface::withAddedException() method description.
     */
    public function addException(\Throwable $exception): void
    {
        $this->exceptions[] = $exception;
    }

    public function withExceptions(iterable $exceptions): self
    {
        $self = clone $this;
        $self->setExceptions($exceptions);

        return $self;
    }

    public function withAddedException(\Throwable $exception): self
    {
        $self = clone $this;
        $self->addException($exception);

        return $self;
    }
}
