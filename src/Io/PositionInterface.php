<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Io;

/**
 * Interface PositionInterface
 */
interface PositionInterface
{
    /**
     * Note: Do not use the return type hint for compatibility with exceptions.
     *
     * @noinspection ReturnTypeCanBeDeclaredInspection
     * @return int
     */
    public function getLine();

    /**
     * Note: Do not use the return type hint for compatibility with exceptions.
     *
     * @noinspection ReturnTypeCanBeDeclaredInspection
     * @return int
     */
    public function getColumn();
}
