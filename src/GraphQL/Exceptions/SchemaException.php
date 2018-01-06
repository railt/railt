<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\Exceptions;

use Railt\GraphQL\Kernel\CallStack;

/**
 * Interface SchemaException
 */
interface SchemaException extends \Throwable
{
    /**
     * SchemaException constructor.
     * @param string $message
     * @param CallStack $stack
     * @param \Throwable|null $previous
     */
    public function __construct(string $message, CallStack $stack, \Throwable $previous = null);

    /**
     * Should return a source code column on which the error occurred.
     *
     * @return int Returns the column offset where the error occurred.
     */
    public function getColumn(): int;

    /**
     * Returns the GraphQL Compiler stack trace.
     *
     * @return array Returns the stack trace as an array.
     */
    public function getCompilerTrace(): array;

    /**
     * @return string
     */
    public function getInfo(): string;
}
