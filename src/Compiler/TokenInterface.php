<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler;

/**
 * Interface TokenInterface
 */
interface TokenInterface
{
    /**
     * @return string|int
     */
    public function name();

    /**
     * @return string
     */
    public function channel(): string;

    /**
     * @return int
     */
    public function offset(): int;

    /**
     * @return string
     */
    public function value(): string;

    /**
     * @param int $offset
     * @return null|string
     */
    public function get(int $offset): ?string;

    /**
     * @return int
     */
    public function bytes(): int;

    /**
     * @return int
     */
    public function length(): int;

    /**
     * @return bool
     */
    public function isEof(): bool;

    /**
     * @return bool
     */
    public function isSystem(): bool;

    /**
     * @return bool
     */
    public function isSkipped(): bool;

    /**
     * @param array|string[]|int[] ...$names
     * @return bool
     */
    public function is(...$names): bool;
}
