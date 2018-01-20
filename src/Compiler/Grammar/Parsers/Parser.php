<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Parsers;

/**
 * Interface Parser
 */
interface Parser
{
    /**
     * @param string $line
     * @return iterable
     */
    public static function parse(string $line): iterable;

    /**
     * @param string $line
     * @return bool
     */
    public static function match(string $line): bool;
}
