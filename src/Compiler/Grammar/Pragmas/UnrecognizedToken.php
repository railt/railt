<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Pragmas;

use Railt\Compiler\Exception\UnrecognizedTokenException;

/**
 * Class UnrecognizedToken
 */
final class UnrecognizedToken extends BasePragma
{
    /**
     * @return string
     */
    public static function getDefaultValue(): string
    {
        return UnrecognizedTokenException::class;
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'error.unrecognized_token';
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
