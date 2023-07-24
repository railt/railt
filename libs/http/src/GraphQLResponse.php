<?php

declare(strict_types=1);

namespace Railt\Http;

use Railt\Contracts\Http\ErrorInterface;

class GraphQLResponse implements MutableResponseInterface
{
    /**
     * @var array<non-empty-string, \Throwable>
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
        /**
         * @psalm-suppress PropertyTypeCoercion
         */
        $this->map = new \WeakMap();

        $this->setExceptions($exceptions);
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function withData(array $data): static
    {
        $self = clone $this;
        $self->setData($data);

        return $self;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function withoutData(): static
    {
        $self = clone $this;
        $self->removeData();

        return $self;
    }

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

            /**
             * @psalm-suppress InaccessibleProperty
             */
            yield $this->map[$exception] ??= GraphQLError::fromException($exception);
        }
    }

    /**
     * @return list<\Throwable>
     */
    public function getExceptions(): array
    {
        return \array_values($this->exceptions);
    }

    public function withExceptions(iterable $exceptions): static
    {
        $self = clone $this;
        $self->setExceptions($exceptions);

        return $self;
    }

    public function setExceptions(iterable $exceptions): void
    {
        foreach ($exceptions as $exception) {
            $this->addException($exception);
        }
    }

    public function withAddedException(\Throwable $exception): static
    {
        $self = clone $this;
        $self->addException($exception);

        return $self;
    }

    public function addException(\Throwable $exception): void
    {
        $this->exceptions[$this->keyOf($exception)] = $exception;
    }

    public function withoutException(\Throwable $exception): static
    {
        $self = clone $this;
        $self->removeException($exception);

        return $self;
    }

    public function removeException(\Throwable $exception): void
    {
        unset($this->exceptions[$this->keyOf($exception)]);
    }

    /**
     * @return non-empty-string
     */
    private function keyOf(\Throwable $exception): string
    {
        /** @var non-empty-string */
        return \spl_object_hash($exception);
    }
}
