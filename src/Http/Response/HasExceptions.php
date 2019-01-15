<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Response;

use Railt\Http\Exception\GraphQLException;
use Railt\Http\Exception\GraphQLExceptionInterface;

/**
 * Trait HasExceptions
 * @mixin ProvideExceptions
 */
trait HasExceptions
{
    /**
     * @var array|\Throwable[]
     */
    protected $exceptions = [];

    /**
     * @return bool
     */
    abstract public function isDebug(): bool;

    /**
     * @return array|\Throwable[]
     */
    public function getExceptions(): array
    {
        return $this->exceptions;
    }

    /**
     * @param \Throwable $exception
     * @return ProvideExceptions|$this
     */
    public function withException(\Throwable $exception): ProvideExceptions
    {
        $this->exceptions[] = $exception;

        return $this;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        $errors = [];

        foreach ($this->exceptions as $exception) {
            if (! $exception instanceof GraphQLExceptionInterface) {
                $exception = GraphQLException::fromThrowable($exception);
            }

            $errors[] = $exception->jsonSerialize();
        }

        return $errors;
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return \count($this->exceptions) > 0;
    }
}
