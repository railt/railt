<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Contracts\Http;

use Railt\Contracts\Common\ArrayableInterface;
use Railt\Contracts\Common\JsonableInterface;
use Railt\Contracts\Http\Response\ExtensionsProviderInterface;

/**
 * Describes an Error found during the parse, validate, or
 * execute phases of performing a GraphQL operation. In addition to a message
 * and stack trace, it also includes information about the locations in a
 * GraphQL document and/or execution result that correspond to the Error.
 *
 * When the error was caused by an exception thrown in resolver, original
 * exception is available via `getPrevious()`.
 *
 * Interface extends standard PHP `\Throwable`, so all standard methods of
 * base `\Throwable` interface are available in addition to those listed below.
 */
interface GraphQLErrorInterface extends
    \Throwable,
    JsonableInterface,
    ArrayableInterface,
    ExtensionsProviderInterface
{
    /**
     * @return array|string[]|int[]
     */
    public function getPath(): array;

    /**
     * @return bool
     */
    public function isPublic(): bool;

    /**
     * @return GraphQLErrorInterface|$this
     */
    public function publish(): self;

    /**
     * @return GraphQLErrorInterface|$this
     */
    public function hide(): self;
}
