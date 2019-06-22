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
 * Interface MutableExceptionsProviderInterface
 */
interface MutableExceptionsProviderInterface extends ExceptionsProviderInterface
{
    /**
     * @param \Throwable ...$e
     * @return MutableExceptionsProviderInterface|$this
     */
    public function withException(\Throwable ...$e): self;

    /**
     * @param \Closure $filter
     * @return MutableExceptionsProviderInterface|$this
     */
    public function withoutException(\Closure $filter): self;

    /**
     * @param array|\Throwable[] $exceptions
     * @return MutableExceptionsProviderInterface
     */
    public function setExceptions(array $exceptions): self;
}
