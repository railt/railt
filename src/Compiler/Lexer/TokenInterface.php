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
 * Interface TokenInterface
 */
interface TokenInterface
{
    /**
     * @return string
     */
    public function name(): string;

    /**
     * @return int
     */
    public function offset(): int;

    /**
     * @param int|null $offset
     * @return string
     */
    public function value(int $offset = null): string;

    /**
     * @return int
     */
    public function bytes(): int;

    /**
     * @return int
     */
    public function length(): int;

    /**
     * @param string[] ...$names
     * @return bool
     */
    public function is(string ...$names): bool;

    /**
     * @return bool
     */
    public function isEof(): bool;
}
