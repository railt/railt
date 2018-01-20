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
 * Class Unicode
 */
final class Unicode extends BasePragma
{
    /**
     * @return bool
     */
    public static function getDefaultValue(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'lexer.unicode';
    }

    /**
     * @param string $value
     * @return bool
     */
    public static function parse(string $value): bool
    {
        return static::parseBoolean($value);
    }
}
