<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Exception\Trace;

use Railt\Component\Position\PositionInterface;

/**
 * Interface ItemInterface
 */
interface ItemInterface extends PositionInterface, Renderable
{
    /**
     * @return string
     */
    public function getFile(): string;

    /**
     * @inheritdoc
     */
    public function getLine(): int;

    /**
     * @inheritdoc
     */
    public function getColumn(): int;
}
