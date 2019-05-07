<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Exceptions;

use Railt\Component\SDL\Runtime\CallStackInterface;

/**
 * Interface SchemaException
 */
interface SchemaException extends \Throwable
{
    /**
     * SchemaException constructor.
     *
     * @param string $message
     * @param CallStackInterface $stack
     * @param \Throwable|null $previous
     */
    public function __construct(string $message, CallStackInterface $stack, \Throwable $previous = null);

    /**
     * Should return a source code column on which the error occurred.
     *
     * @return int Returns the column offset where the error occurred.
     */
    public function getColumn(): int;
}
