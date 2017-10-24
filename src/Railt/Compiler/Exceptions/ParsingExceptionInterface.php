<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Exceptions;

/**
 * Interface ParsingExceptionInterface
 */
interface ParsingExceptionInterface extends \Throwable
{
    /**
     * @return string
     */
    public function getFile()/*: string*/;

    /**
     * @return int
     */
    public function getLine()/*: int*/;

    /**
     * @return int
     */
    public function getColumn(): int;
}
