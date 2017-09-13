<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Abstraction\Common;

/**
 * Interface HasDescription
 * @package Railt\Reflection\Abstraction\Common
 */
interface HasDescription
{
    /**
     * @return string
     */
    public function getDescription(): string;
}
