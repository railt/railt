<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Exceptions;

/**
 * An error occurred during the initialization of the compiler.
 */
class CompilerException extends \LogicException
{
    /**
     * @param \Throwable $e
     * @return CompilerException
     */
    public static function wrap(\Throwable $e): self
    {
        return new static($e->getMessage(), $e->getCode(), $e);
    }
}
