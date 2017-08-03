<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Compiler\Exceptions;

use Throwable;

/**
 * Class TypeException
 * @package Serafim\Railgun\Compiler\Exceptions
 */
class TypeException extends SemanticException
{
    /**
     * TypeError constructor.
     * @param string $message
     * @param null|string $file
     * @param int|null $line
     * @param int $code
     * @param null|Throwable $previous
     */
    public function __construct(
        string $message,
        ?string $file,
        ?int $line = 0,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);

        $this->file = $file ?? 'php://input';
        $this->line = $line ?? $line;
    }
}
