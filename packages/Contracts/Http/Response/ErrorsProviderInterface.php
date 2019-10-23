<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Contracts\Http\Response;

use Railt\Contracts\Http\GraphQLErrorInterface;

/**
 * Interface ErrorsProviderInterface
 */
interface ErrorsProviderInterface
{
    /**
     * @return iterable|GraphQLErrorInterface[]
     */
    public function getErrors(): iterable;

    /**
     * @param string $message
     * @param array $locations
     * @param array $path
     * @return ErrorsProviderInterface|$this
     */
    public function withError(string $message, array $locations = [], array $path = []): self;

    /**
     * @param string $message
     * @param array $locations
     * @param array $path
     * @return ErrorsProviderInterface|$this
     */
    public function withClientError(string $message, array $locations = [], array $path = []): self;

    /**
     * @return bool
     */
    public function hasErrors(): bool;
}
