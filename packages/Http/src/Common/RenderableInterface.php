<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Common;

/**
 * Interface RenderableInterface
 */
interface RenderableInterface extends \JsonSerializable
{
    /**
     * @return array
     */
    public function toArray(): array;
}
