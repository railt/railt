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
 * The lexical token that returns from stream.
 */
interface TokenInterface
{
    /**
     * End of input token
     */
    public const END_OF_INPUT = 'T_EOI';

    /**
     * Unknown token
     */
    public const UNKNOWN_TOKEN = 'T_UNKNOWN';

    /**
     * Token name.
     *
     * @return string
     */
    public function name(): string;

    /**
     * Token position in bytes.
     *
     * @return int
     */
    public function offset(): int;

    /**
     * Returns the value of the captured subgroup.
     *
     * @param int $group Number of subgroup
     * @return string|null If the group is not found, the null will return.
     */
    public function value(int $group = 0): ?string;

    /**
     * The token value size in bytes.
     *
     * @return int
     */
    public function bytes(): int;

    /**
     * The token value size in chars (multibyte encodings contain several bytes).
     *
     * @return int
     */
    public function length(): int;
}
