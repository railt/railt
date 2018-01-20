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
 * Class SkippedTokenDefinitions
 */
class SkippedTokenDefinitions extends TokenDefinitions
{
    private const PREFIX_SKIP     = '%skip';
    private const PREFIX_SKIP_LEN = 5;

    /**
     * @param string $line
     * @return iterable
     */
    public static function parse(string $line): iterable
    {
        [$name, $value] = self::matches(
            self::withoutPrefix($line, self::PREFIX_SKIP_LEN)
        );

        return [$name => [$value, false]];
    }

    /**
     * @param string $line
     * @return bool
     */
    public static function match(string $line): bool
    {
        return self::startsWith($line, self::PREFIX_SKIP);
    }
}
