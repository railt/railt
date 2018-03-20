<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Lexer\Result;

use Railt\Compiler\Lexer\TokenInterface;

/**
 * Class Eof
 */
final class Eof implements TokenInterface
{
    public const NAME = 'T_EOF';

    /**
     * @var int
     */
    private $offset;

    /**
     * EndOfFile constructor.
     * @param int $offset
     */
    public function __construct(int $offset)
    {
        $this->offset = $offset;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return self::NAME;
    }

    /**
     * @return int
     */
    public function offset(): int
    {
        return $this->offset;
    }

    /**
     * @param int|null $offset
     * @return string
     */
    public function value(int $offset = null): string
    {
        return "\0";
    }

    /**
     * @return int
     */
    public function bytes(): int
    {
        return 0;
    }

    /**
     * @return int
     */
    public function length(): int
    {
        return 0;
    }

    /**
     * @return bool
     */
    public function isSkipped(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return '"\0" (' . $this->name() . ')';
    }

    /**
     * @param string[] ...$names
     * @return bool
     */
    public function is(string ...$names): bool
    {
        return \in_array($this->name(), $names, true);
    }

    /**
     * @return bool
     */
    public function isEof(): bool
    {
        return true;
    }
}
