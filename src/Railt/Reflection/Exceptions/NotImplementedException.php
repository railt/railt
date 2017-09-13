<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Exceptions;

/**
 * Class NotImplementedException
 * @package Railt\Reflection\Exceptions
 */
class NotImplementedException extends \LogicException
{
    use ExceptionHelper;

    /**
     * NotImplementedException constructor.
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        if (!$message) {
            $ctx = $this->getPreviousContext();

            $message = isset($ctx['class'], $ctx['function'])
                ? sprintf('Method %s::%s() not implemented yet.', $ctx['class'], $ctx['function'])
                : 'Not implemented yet.';

            if (isset($ctx['file'])) {
                $this->in($ctx['file'], $ctx['line']);
            }
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array
     */
    private function getPreviousContext(): array
    {
        return debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[2] ?? [];
    }
}
