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
     * @var string[]
     */
    private const LINKER_MAPPINGS = [
        LinkerInterface::LINK_ENUM_TYPE         => 'Enum',
        LinkerInterface::LINK_INPUT_OBJECT_TYPE => 'InputObject',
        LinkerInterface::LINK_INTERFACE_TYPE    => 'Interface',
        LinkerInterface::LINK_OBJECT_TYPE       => 'Object',
        LinkerInterface::LINK_SCALAR_TYPE       => 'Scalar',
        LinkerInterface::LINK_UNION_TYPE        => 'Union',
        LinkerInterface::LINK_DIRECTIVE         => 'Directive',
        LinkerInterface::LINK_SCHEMA            => 'Schema',
    ];

    /**
     * @param int $type
     * @return string
     */
    public static function toString(int $type): string
    {
        $result = static::toArray($type);

        return \count($result) ? \implode('|', $result) : 'unknown';
    }

    /**
     * @param int $type
     * @return array|string[]
     */
    public static function toArray(int $type): array
    {
        $result = [];

        foreach (self::LINKER_MAPPINGS as $code => $description) {
            if (($code & $type) === $code) {
                $result[] = $description;
            }
        }

        return $result;
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
