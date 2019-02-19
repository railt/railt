<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Application;

use Railt\Foundation\Extension\ExtensionInterface;

/**
 * Interface ProvidesExtensions
 */
interface ProvidesExtensions
{
    /**
     * @param string|ExtensionInterface $extension
     * @return ProvidesExtensions|$this
     */
    public function extend(string $extension): self;
}
