<?php

declare(strict_types=1);

namespace Railt\Http;

use Railt\Contracts\Http\ResponseInterface;

interface MutableResponseInterface extends ResponseInterface
{
    /**
     * Mutable equivalent of {@see DataProviderInterface::withData()} method.
     *
     * @link DataProviderInterface::withData() method description.
     */
    public function setData(array $data): void;

    /**
     * Mutable equivalent of {@see DataProviderInterface::withoutData()} method.
     *
     * @link DataProviderInterface::withoutData() method description.
     */
    public function removeData(): void;

    /**
     * Mutable equivalent of {@see ExceptionProviderInterface::withExceptions()} method.
     *
     * @link ExceptionProviderInterface::withExceptions() method description.
     *
     * @param iterable<\Throwable> $exceptions
     */
    public function setExceptions(iterable $exceptions): void;

    /**
     * Mutable equivalent of {@see ExceptionProviderInterface::withAddedException()} method.
     *
     * @link ExceptionProviderInterface::withAddedException() method description.
     */
    public function addException(\Throwable $exception): void;

    /**
     * Mutable equivalent of {@see ExceptionProviderInterface::withoutException()} method.
     *
     * @link ExceptionProviderInterface::withoutException() method description.
     */
    public function removeException(\Throwable $exception): void;
}
