<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Contracts\Definitions\Directive;

/**
 * Interface Location
 */
interface Location
{
    /**
     * Location adjacent to an document (global).
     */
    public const TARGET_DOCUMENT = 'DOCUMENT';

    /**
     * Location adjacent to an enum definition.
     */
    public const TARGET_ENUM = 'ENUM';

    /**
     * Location adjacent to a query operation.
     */
    public const TARGET_QUERY = 'QUERY';

    /**
     * Location adjacent to a union definition.
     */
    public const TARGET_UNION = 'UNION';

    /**
     * Location adjacent to a field.
     */
    public const TARGET_FIELD = 'FIELD';

    /**
     * Location adjacent to a scalar definition.
     */
    public const TARGET_SCALAR = 'SCALAR';

    /**
     * Location adjacent to a schema definition.
     */
    public const TARGET_SCHEMA = 'SCHEMA';

    /**
     * Location adjacent to an object type definition.
     */
    public const TARGET_OBJECT = 'OBJECT';

    /**
     * Location adjacent to a mutation operation.
     */
    public const TARGET_MUTATION = 'MUTATION';

    /**
     * Location adjacent to an interface definition.
     */
    public const TARGET_INTERFACE = 'INTERFACE';

    /**
     * Location adjacent to an enum value definition.
     */
    public const TARGET_ENUM_VALUE = 'ENUM_VALUE';

    /**
     * Location adjacent to an input object type definition.
     */
    public const TARGET_INPUT_OBJECT = 'INPUT_OBJECT';

    /**
     * Location adjacent to a subscription operation.
     */
    public const TARGET_SUBSCRIPTION = 'SUBSCRIPTION';

    /**
     * Location adjacent to a fragment spread.
     */
    public const TARGET_FRAGMENT_SPREAD = 'FRAGMENT_SPREAD';

    /**
     * Location adjacent to an inline fragment.
     */
    public const TARGET_INLINE_FRAGMENT = 'INLINE_FRAGMENT';

    /**
     * Location adjacent to a field definition.
     */
    public const TARGET_FIELD_DEFINITION = 'FIELD_DEFINITION';

    /**
     * Location adjacent to a fragment definition.
     */
    public const TARGET_FRAGMENT_DEFINITION = 'FRAGMENT_DEFINITION';

    /**
     * Location adjacent to an argument definition.
     */
    public const TARGET_ARGUMENT_DEFINITION = 'ARGUMENT_DEFINITION';

    /**
     * Location adjacent to an input object field definition.
     */
    public const TARGET_INPUT_FIELD_DEFINITION = 'INPUT_FIELD_DEFINITION';

    /**
     * Locations using in graphql queries
     */
    public const TARGET_GRAPHQL_QUERY = [
        self::TARGET_FIELD,
        self::TARGET_QUERY,
        self::TARGET_MUTATION,
        self::TARGET_SUBSCRIPTION,
        self::TARGET_FRAGMENT_DEFINITION,
        self::TARGET_FRAGMENT_SPREAD,
        self::TARGET_INLINE_FRAGMENT,
    ];

    /**
     * Locations using in graphql schema definitions
     */
    public const TARGET_GRAPHQL_SDL = [
        self::TARGET_SCHEMA,
        self::TARGET_OBJECT,
        self::TARGET_INPUT_OBJECT,
        self::TARGET_INPUT_FIELD_DEFINITION,
        self::TARGET_ENUM,
        self::TARGET_ENUM_VALUE,
        self::TARGET_UNION,
        self::TARGET_INTERFACE,
        self::TARGET_SCALAR,
        self::TARGET_FIELD_DEFINITION,
        self::TARGET_ARGUMENT_DEFINITION,

        // Non-standard
        self::TARGET_DOCUMENT,
    ];
}
