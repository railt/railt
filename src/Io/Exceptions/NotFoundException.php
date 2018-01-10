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
 * Class NotFoundException
 */
class NotFoundException extends NotReadableException
{
    /**
     * @param string $file
     * @param \Throwable|null $previous
     * @return NotReadableException|NotFoundException
     */
    public static function fromFilePath(string $file = '', \Throwable $previous = null): NotReadableException
    {
        $message = \sprintf('File "%s" not found.', $file);

        return new static($message, 0, $previous);
    }
}
