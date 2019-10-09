<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Exception;

use Ramsey\Collection\CollectionInterface;

/**
 * Trait ExceptionsTrait
 *
 * @mixin ExceptionsProviderInterface
 */
trait ExceptionsTrait
{
    /**
     * @var CollectionInterface|\Throwable[]
     */
    private CollectionInterface $exceptions;

    /**
     * @param array|\Throwable[]|CollectionInterface $exceptions
     * @return void
     */
    protected function setExceptions(iterable $exceptions): void
    {
        if ($exceptions instanceof CollectionInterface) {
            $exceptions = $exceptions->toArray();
        }

        $this->exceptions = new ExceptionsCollection($exceptions);
    }

    /**
     * @param \Throwable $exception
     * @return ExceptionsProviderInterface
     */
    public function withException(\Throwable $exception): ExceptionsProviderInterface
    {
        $self = clone $this;

        $self->exceptions->add($exception);

        return $self;
    }

    /**
     * @param iterable|\Throwable[]|CollectionInterface $exceptions
     * @return ExceptionsProviderInterface
     */
    public function withExceptions(iterable $exceptions): ExceptionsProviderInterface
    {
        $self = clone $this;

        foreach ($exceptions as $exception) {
            $self->exceptions->add($exception);
        }

        return $self;
    }

    /**
     * @return CollectionInterface|GraphQLExceptionInterface[]
     */
    public function getExceptions(): CollectionInterface
    {
        return $this->exceptions;
    }

    /**
     * @return bool
     */
    public function hasExceptions(): bool
    {
        return ! $this->exceptions->isEmpty();
    }
}
