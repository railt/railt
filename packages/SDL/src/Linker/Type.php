<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Linker;

/**
 * Class Type
 */
final class Type
{
    /**
     * @param int $type
     * @return string
     */
    public static function toString(int $type): string
    {
        switch (true) {
            case self::wantsType($type):
                return 'type';

            case self::wantsDirective($type):
                return 'directive';

            case self::wantsSchema($type):
                return 'schema';

            default:
                return 'unknown';
        }
    }

    /**
     * @param int $type
     * @return bool
     */
    public static function wantsType(int $type): bool
    {
        return ($type & LinkerInterface::LINK_TYPE) === $type;
    }

    /**
     * @param int $type
     * @return bool
     */
    public static function wantsDirective(int $type): bool
    {
        return ($type & LinkerInterface::LINK_DIRECTIVE) === $type;
    }

    /**
     * @param int $type
     * @return bool
     */
    public static function wantsSchema(int $type): bool
    {
        return ($type & LinkerInterface::LINK_SCHEMA) === $type;
    }
}
