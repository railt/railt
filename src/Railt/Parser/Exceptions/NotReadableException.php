<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Exceptions;

/**
 * Class NotReadableException
 * @package Railt\Parser\Exceptions
 */
class NotReadableException extends \LogicException
{
    /**
     * NotReadableException constructor.
     * @param string $file
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $file = '', int $code = 0, \Throwable $previous = null)
    {
        $suffix = is_file($file)
            ? 'Probably not enough permissions to read the file.'
            : 'File not found.';

        $message = sprintf('File "%s" not readable. %s', $file, $suffix);

        parent::__construct($message, $code, $previous);
    }
}
