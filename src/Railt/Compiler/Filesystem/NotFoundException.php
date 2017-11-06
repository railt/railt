<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Filesystem;

/**
 * Class NotFoundException
 */
class NotFoundException extends NotReadableException
{
    /**
     * NotFoundException constructor.
     * @param string $file
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $file = '', int $code = 0, \Throwable $previous = null)
    {
        $message = sprintf('File "%s" not found.', $file);

        parent::__construct($message, $code, $previous);
    }
}
