<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Lexer;

/**
 * Class TokenStructure
 */
final class Token
{
    /**
     * Default token namespace.
     */
    public const T_DEFAULT_NAMESPACE = 'default';

    /**
     * End of file token name.
     */
    public const T_EOF_NAME = 'EOF';

    /**#@+
     * Indices of the resulting data structure.
     */
    public const T_TOKEN        = 'token';
    public const T_VALUE        = 'value';
    public const T_LENGTH       = 'length';
    public const T_NAMESPACE    = 'namespace';
    public const T_KEEP         = 'keep';
    public const T_OFFSET       = 'offset';
    /**#@-*/

    /**
     * @param int $offset
     * @return array
     */
    public static function eof(int $offset): array
    {
        return [
            static::T_TOKEN     => static::T_EOF_NAME,
            static::T_VALUE     => static::T_EOF_NAME,
            static::T_LENGTH    => 0,
            static::T_NAMESPACE => static::T_DEFAULT_NAMESPACE,
            static::T_KEEP      => true,
            static::T_OFFSET    => $offset,
        ];
    }
}
