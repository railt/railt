<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Exceptions;

use Hoa\Compiler\Exception\UnrecognizedToken;
use Railt\Compiler\Filesystem\ReadableInterface;

/**
 * Class ParsingException
 * @deprecated Will be replaced by an internal compiler exception
 */
class ParsingException extends \ParseError implements ParsingExceptionInterface
{
    use Helper;

    /**
     * ParsingException constructor.
     * @param string $message
     * @param int $code
     * @param \Throwable $previous
     * @param ReadableInterface|null $file
     */
    public function __construct(string $message, int $code = 0, \Throwable $previous, ReadableInterface $file = null)
    {
        parent::__construct($message, $code ?: $previous->getCode(), $previous);

        if ($file !== null) {
            $this->in($file->getPathname());
        }

        if ($previous instanceof UnrecognizedToken) {
            $this->on($previous->getLine(), $previous->getColumn());
        }
    }
}
