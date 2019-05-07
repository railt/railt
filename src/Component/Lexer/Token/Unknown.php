<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Lexer\Token;

/**
 * Class Unknown
 */
final class Unknown extends Token
{
    /**
     * Unknown token name.
     */
    public const T_NAME = 'T_UNKNOWN';

    /**
     * Undefined constructor.
     *
     * @param string $value
     * @param int $offset
     */
    public function __construct(string $value, int $offset = 0)
    {
        parent::__construct(static::T_NAME, $value, $offset);
    }
}
