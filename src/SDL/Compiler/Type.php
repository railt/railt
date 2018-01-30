<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

/**
 * Class Type
 */
final class Type
{
    /**#@+
     * Type names list
     */
    // Location: "DOCUMENT"
    public const DOCUMENT = 'Document';

    // Location: "SCHEMA"
    public const SCHEMA = 'Schema';

    // Location: "OBJECT"
    public const OBJECT = 'Object';

    // Location: "INTERFACE"
    public const INTERFACE = 'Interface';

    // Location: "FIELD_DEFINITION"
    public const FIELD = 'Field';

    // Location: "ARGUMENT_DEFINITION"
    public const ARGUMENT = 'Argument';

    // Location: ~~Invalid~~
    public const DIRECTIVE = 'Directive';

    // Location: ~~Invalid~~
    public const DIRECTIVE_ARGUMENT = 'DirectiveArgument';

    // Location: "INPUT_OBJECT"
    public const INPUT = 'Input';

    // Location: "INPUT_FIELD_DEFINITION"
    public const INPUT_FIELD = 'InputField';

    // Location: "ENUM"
    public const ENUM = 'Enum';

    // Location: "ENUM_VALUE"
    public const ENUM_VALUE = 'EnumValue';

    // Location: "UNION"
    public const UNION = 'Union';

    // Location: "SCALAR"
    public const SCALAR = 'Scalar';
    /**#@-*/

    /**#@+
     * Type Extensions
     */
    // TODO Think of how it should look like ♥
    // public const EXTENSION_DOCUMENT  = 'DocumentExtension';
    public const EXTENSION_OBJECT = 'ObjectExtension';
    public const EXTENSION_SCHEMA = 'SchemaExtension';
    public const EXTENSION_INTERFACE = 'InterfaceExtension';
    public const EXTENSION_DIRECTIVE = 'DirectiveExtension';
    public const EXTENSION_INPUT = 'InputExtension';
    public const EXTENSION_ENUM = 'EnumExtension';
    public const EXTENSION_UNION = 'UnionExtension';
    public const EXTENSION_SCALAR = 'ScalarExtension';
    /**#@-*/

    private const EXTENSIONS = [
        self::EXTENSION_OBJECT,
        self::EXTENSION_SCHEMA,
        self::EXTENSION_INTERFACE,
        self::EXTENSION_DIRECTIVE,
        self::EXTENSION_INPUT,
        self::EXTENSION_ENUM,
        self::EXTENSION_UNION,
        self::EXTENSION_SCALAR,
    ];

    /**
     * @param string $type
     * @return bool
     */
    public static function isExtension(string $type): bool
    {
        return \in_array($type, self::EXTENSIONS, true);
    }
}
