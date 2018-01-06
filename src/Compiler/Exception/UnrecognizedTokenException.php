<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Exception;

/**
 * Class UnrecognizedToken
 */
class UnrecognizedTokenException extends LexerException
{
    use Support;

    /**
     * UnrecognizedTokenException constructor.
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     * @param array $parameters
     */
    public function __construct(string $message, int $code = 0, \Throwable $previous = null, array $parameters = [])
    {
        $message .= $this->suffix($parameters['input'], $parameters['offset']);

        parent::__construct($message, $code, $previous);
    }
}
