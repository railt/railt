<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Contracts;

/**
 * Interface Type
 */
interface Type
{
    /**#@+
     * Virtual types group
     */
    public const DOCUMENT           = 'Document';
    /**#@-*/

    /**#@+
     * SDL types
     */
    public const SCHEMA             = 'Schema';

    public const OBJECT             = 'Object';
    public const OBJECT_FIELD       = 'Field';      // TODO Rename
    public const OBJECT_ARGUMENT    = 'Argument';   // TODO Rename

    public const INTERFACE          = 'Interface';
    public const INTERFACE_FIELD    = 'Field';      // TODO Rename
    public const INTERFACE_ARGUMENT = 'Argument';   // TODO Rename

    public const DIRECTIVE          = 'Directive';
    public const DIRECTIVE_ARGUMENT = 'Argument';   // TODO Rename

    public const INPUT              = 'Input';
    public const INPUT_FIELD        = 'InputField';

    public const ENUM               = 'Enum';
    public const ENUM_VALUE         = 'EnumValue';

    public const UNION              = 'Union';
    public const SCALAR             = 'Scalar';
    /**#@-*/

    /**#@+
     * Invocations
     */
    public const INPUT_INVOCATION       = self::INPUT;
    public const DIRECTIVE_INVOCATION   = self::DIRECTIVE;
    // Extensions
    public const EXTENSION          = 'Extension';
    /**#@-*/
}
