<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Pragmas;

/**
 * Class Lookahead
 */
final class Lookahead extends BasePragma
{
    /**
     * @return int
     */
    public static function getDefaultValue(): int
    {
        return 1024;
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'parser.lookahead';
    }

    /**
     * @param string $value
     * @return int
     */
    public static function parse(string $value): int
    {
        return static::parseInt($value);
    }
}
