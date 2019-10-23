<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Response;

use Railt\Http\GraphQLError;
use Railt\Contracts\Http\Response\ErrorsProviderInterface;
use Railt\Contracts\Http\Response\ExceptionsProviderInterface;

/**
 * Trait ExceptionsTrait
 *
 * @mixin ErrorsProviderInterface
 */
trait ErrorsTrait
{
    use ExceptionsTrait;

    /**
     * {@inheritDoc}
     */
    public function getErrors(): iterable
    {
        return $this->exceptions;
    }

    /**
     * {@inheritDoc}
     * @return ErrorsProviderInterface|ExceptionsProviderInterface
     */
    public function withError(string $message, array $locations = [], array $path = []): ErrorsProviderInterface
    {
        return $this->withException(GraphQLError::create($message, $locations, $path));
    }

    /**
     * {@inheritDoc}
     * @return ErrorsProviderInterface|ExceptionsProviderInterface
     */
    public function withClientError(string $message, array $locations = [], array $path = []): ErrorsProviderInterface
    {
        return $this->withClientException(GraphQLError::create($message, $locations, $path));
    }

    /**
     * {@inheritDoc}
     */
    public function hasErrors(): bool
    {
        return $this->exceptions !== [];
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return ! $this->hasErrors();
    }

    /**
     * @return bool
     */
    public function isInvalid(): bool
    {
        return $this->hasErrors();
    }
}
