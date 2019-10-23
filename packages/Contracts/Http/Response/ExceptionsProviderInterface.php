<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Contracts\Http\Response;

/**
 * Interface ExceptionsProviderInterface
 */
interface ExceptionsProviderInterface
{
    /**
     * @return iterable|\Throwable[]
     */
    public function getExceptions(): iterable;

    /**
     * @param \Throwable ...$exceptions
     * @return ExceptionsProviderInterface|$this|static
     */
    public function withException(\Throwable ...$exceptions): self;

    /**
     * @param \Throwable ...$exceptions
     * @return ExceptionsProviderInterface|$this|static
     */
    public function withClientException(\Throwable ...$exceptions): self;

    /**
     * @return bool
     */
    public function hasExceptions(): bool;
}
