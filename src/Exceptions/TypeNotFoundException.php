<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Exceptions;

/**
 * Class TypeNotFoundException
 * @package Railgun\Exceptions
 */
class TypeNotFoundException extends RuntimeException
{
    /**
     * @param string $type
     * @return static|TypeNotFoundException
     */
    public static function basic(string $type): TypeNotFoundException
    {
        return static::new('Type "%s" not found.', $type);
    }

    /**
     * @param string $type
     * @return static|TypeNotFoundException
     */
    public static function fromLoader(string $type): TypeNotFoundException
    {
        return static::new('Type "%s" not found and could not be loaded.', $type);
    }
}
