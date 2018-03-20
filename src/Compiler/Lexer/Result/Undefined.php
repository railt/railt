<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Lexer\Result;

/**
 * Class Undefined
 */
final class Undefined extends Token
{
    public const NAME = 'T_UNDEFINED';

    /**
     * Undefined constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        parent::__construct(self::NAME, $value);
    }

    /**
     * @return bool
     */
    public function isEof(): bool
    {
        return true;
    }
}
