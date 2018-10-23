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
 * Class Unknown
 */
final class Unknown extends Token
{
    /**
     * Undefined constructor.
     * @param string $value
     * @param int $offset
     */
    public function __construct(string $value, int $offset = 0)
    {
        parent::__construct(self::UNKNOWN_TOKEN, $value, $offset);
    }
}
