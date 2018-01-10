<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Io\Exceptions;

/**
 * Class NotReadableException
 */
class NotReadableException extends \LogicException
{
    /**
     * @param string $file
     * @param \Throwable|null $previous
     * @return NotReadableException
     */
    public static function fromFilePath(string $file = '', \Throwable $previous = null): self
    {
        $message = \sprintf('File "%s" not readable.', $file);

        return new static($message, 0, $previous);
    }
}
