<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Response;

/**
 * Interface Renderable
 */
interface Renderable extends \JsonSerializable
{
    /**
     * @return string
     */
    public function render(): string;

    /**
     * @return void
     */
    public function send(): void;
}
