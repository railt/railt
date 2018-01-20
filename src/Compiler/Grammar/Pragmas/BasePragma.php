<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Pragmas;

use Railt\Compiler\Exception\InvalidPragmaValueException;

/**
 * Class BasePragma
 */
abstract class BasePragma implements Pragma
{
    /**
     * @param string $value
     * @return bool|null
     */
    protected static function parseBoolean(string $value): bool
    {
        switch (\mb_strtolower($value)) {
            case 'true':
            case '1':
                return true;
            case 'false':
            case '0':
                return false;
        }

        $error = 'The value of "%s" pragma must be a boolean, but %s given';
        throw new InvalidPragmaValueException(\sprintf($error, static::getName(), $value));
    }

    /**
     * @param string $value
     * @return int
     */
    protected static function parseInt(string $value): int
    {
        if ($value === (string)(int)$value) {
            return (int)$value;
        }

        $error = 'The value of "%s" pragma must be an integer, but %s given';
        throw new InvalidPragmaValueException(\sprintf($error, static::getName(), $value));
    }

    /**
     * @param string $value
     * @return string
     */
    protected static function parseClass(string $value): string
    {
        if (\class_exists($value)) {
            return $value;
        }

        $error = 'The value of "%s" pragma must be a valid class name, but %s given';
        throw new InvalidPragmaValueException(\sprintf($error, static::getName(), $value));
    }
}
