<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Pragmas;

use Railt\Compiler\Exception\UnexpectedTokenException;

/**
 * Class UnexpectedToken
 */
final class UnexpectedToken extends BasePragma
{
    /**
     * @return string
     */
    public static function getDefaultValue(): string
    {
        return UnexpectedTokenException::class;
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'error.unexpected_token';
    }

    /**
     * @param string $value
     * @return string
     */
    public static function parse(string $value): string
    {
        return static::parseClass($value);
    }
}
