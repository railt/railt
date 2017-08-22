<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Exceptions;

/**
 * Class CompilerException
 * @package Railt\Exceptions
 */
class CompilerException extends \TypeError
{
    use RailtException;

    /**
     * CompilerException constructor.
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        $message = 'Internal compiler exception: ' . $message;

        parent::__construct($message, $code, $previous);
    }
}
