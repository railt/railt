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
 * Class BaseParser
 */
abstract class BaseParser implements Parser
{
    /**
     * @param string $line
     * @param string $prefix
     * @return bool
     */
    protected static function startsWith(string $line, string $prefix): bool
    {
        return \strpos($line, $prefix) === 0;
    }

    /**
     * @param string $line
     * @param int $len
     * @return string
     */
    protected static function withoutPrefix(string $line, int $len): string
    {
        return \ltrim(\substr($line, $len));
    }
}
