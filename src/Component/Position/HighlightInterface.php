<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Position;

/**
 * Interface HighlightInterface
 */
interface HighlightInterface
{
    /**
     * @param int $from
     * @param int $length
     * @return string
     */
    public function render(int $from = 1, int $length = null): string;

    /**
     * @param string $message
     * @param int $from
     * @param int|null $length
     * @return string
     */
    public function renderWithMessage(string $message, int $from = 1, int $length = null): string;
}
