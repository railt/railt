<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Exceptions;

use Railt\SDL\Compiler\Runtime\CallStackInterface;

/**
 * Interface CompilerException
 */
interface CompilerException extends \Throwable
{
    /**
     * SchemaException constructor.
     * @param string $message
     * @param CallStackInterface $stack
     * @param \Throwable|null $previous
     */
    public function __construct(string $message, CallStackInterface $stack, \Throwable $previous = null);

    /**
     * @return int
     */
    public function getColumn(): int;
}
