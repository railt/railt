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
use Railt\Contracts\Http\GraphQLErrorInterface;
use Railt\Contracts\Http\Response\ExceptionsProviderInterface;

/**
 * Trait ExceptionsTrait
 *
 * @mixin ExceptionsProviderInterface
 */
trait ExceptionsTrait
{
    /**
     * @var array|GraphQLErrorInterface[]
     */
    private array $exceptions = [];

    /**
     * {@inheritDoc}
     */
    public function withException(\Throwable ...$exceptions): ExceptionsProviderInterface
    {
        $self = clone $this;
        $self->setExceptions($exceptions);

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function withClientException(\Throwable ...$exceptions): ExceptionsProviderInterface
    {
        $self = clone $this;

        $map = static function (\Throwable $e): GraphQLErrorInterface {
            if ($e instanceof GraphQLErrorInterface) {
                return $e->publish();
            }

            return GraphQLError::fromThrowable($e)->publish();
        };

        $self->setExceptions(\array_map($map, $exceptions));

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function getExceptions(): iterable
    {
        $cb = function () {
            foreach ($this->exceptions as $exception) {
                if ($exception instanceof GraphQLError && $root = $exception->getException()) {
                    yield $root;

                    continue;
                }

                yield $exception;
            }
        };

        return [...$cb()];
    }

    /**
     * {@inheritDoc}
     */
    protected function setExceptions(iterable $exceptions): void
    {
        foreach ($exceptions as $exception) {
            if (! $exception instanceof GraphQLErrorInterface) {
                $exception = GraphQLError::fromThrowable($exception);
            }

            $this->exceptions[] = $exception;
        }
    }

    /**
     *{@inheritDoc}
     */
    public function hasExceptions(): bool
    {
        return $this->exceptions !== [];
    }
}
