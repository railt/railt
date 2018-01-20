<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Parsers;

use Railt\Compiler\Exception\InvalidTokenException;

/**
 * Class TokenDefinitions
 */
class TokenDefinitions extends BaseParser
{
    private const PREFIX_TOKEN     = '%token';
    private const PREFIX_TOKEN_LEN = 6;

    /**
     * @param string $line
     * @return iterable
     */
    public static function parse(string $line): iterable
    {
        [$name, $value] = self::matches(
            self::withoutPrefix($line, self::PREFIX_TOKEN_LEN)
        );

        return [$name => [$value, true]];
    }

    /**
     * @param string $line
     * @return bool
     */
    public static function match(string $line): bool
    {
        return self::startsWith($line, self::PREFIX_TOKEN);
    }

    /**
     * @param string $line
     * @return array
     */
    protected static function matches(string $line): array
    {
        // [1 => namespace, 2 => name, 3 => body]
        $result = \preg_match('/^([^\h]+)\h+(.*?)$/u', $line, $matches);

        if ($result === false || $result === 0 || \count($matches) !== 3) {
            $error = 'Could not parse the "%s" token definition.';
            throw new InvalidTokenException(\sprintf($error, $line));
        }

        return [$matches[1], $matches[2]];
    }
}
