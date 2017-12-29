<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Exception;

/**
 * Class Exception
 */
class Exception extends \LogicException
{
    /**
     * Exception constructor.
     * @param string $message
     * @param int $code
     * @param array $args
     * @param \Throwable|null $previous
     */
    public function __construct(string $message, int $code = 0, array $args = [], \Throwable $previous = null)
    {
        parent::__construct(\vsprintf($message, $args), $code, $previous);
    }
}
