<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Parsers;

use Railt\Compiler\Exception\InvalidPragmaException;
use Railt\Compiler\Grammar\Pragmas\Lookahead;
use Railt\Compiler\Grammar\Pragmas\Pragma;
use Railt\Compiler\Grammar\Pragmas\UnexpectedToken;
use Railt\Compiler\Grammar\Pragmas\Unicode;
use Railt\Compiler\Grammar\Pragmas\UnrecognizedToken;

/**
 * Class Pragmas
 */
class Pragmas extends BaseParser
{
    private const PREFIX_PRAGMA     = '%pragma';
    private const PREFIX_PRAGMA_LEN = 7;

    /**
     * @return array|Pragma[]
     */
    private static function getAllowedPragmas(): array
    {
        return [
            Unicode::class,
            Lookahead::class,
            UnexpectedToken::class,
            UnrecognizedToken::class,
        ];
    }

    /**
     * @param string $line
     * @return iterable
     */
    public static function parse(string $line): iterable
    {
        [$name, $value] = self::matches(
            self::withoutPrefix($line, self::PREFIX_PRAGMA_LEN)
        );

        yield $name => self::resolve($name, $value);
    }

    /**
     * @param string $line
     * @return array
     */
    protected static function matches(string $line): array
    {
        $result = \preg_match('/^([^\h]+)\h+(.*)$/u', $line, $matches);

        if ($result === false || $result === 0 || \count($matches) !== 3) {
            $error = 'Could not parse the "%s" pragma definition.';
            throw new InvalidPragmaException(\sprintf($error, $line));
        }

        return [$matches[1], $matches[2]];
    }

    /**
     * @param string $name
     * @param string $value
     * @return mixed
     */
    private static function resolve(string $name, string $value)
    {
        foreach (self::getAllowedPragmas() as $pragma) {
            if ($pragma::getName() === $name) {
                return $pragma::parse($value);
            }
        }

        $error = 'Unrecognized pragma "%s" definition.';

        throw new InvalidPragmaException(\sprintf($error, $name));
    }

    /**
     * @param string $line
     * @return bool
     */
    public static function match(string $line): bool
    {
        return static::startsWith($line, self::PREFIX_PRAGMA);
    }

    /**
     * @param array $pragmas
     * @return array
     */
    public static function withDefaults(array $pragmas): array
    {
        foreach (self::getAllowedPragmas() as $pragma) {
            if (! \array_key_exists($pragma::getName(), $pragmas)) {
                $pragmas[$pragma::getName()] = $pragma::getDefaultValue();
            }
        }

        return $pragmas;
    }

    /**
     * @param array $pragmas
     * @param string|Pragma $pragma
     * @return mixed
     */
    public static function get(array $pragmas, string $pragma)
    {
        return $pragmas[$pragma::getName()] ?? $pragma::getDefaultValue();
    }
}
