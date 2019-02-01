<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Config;

/**
 * Interface PreloadingConfigurationInterface
 */
interface PreloadingConfigurationInterface
{
    /**
     * @return array|string[]
     */
    public function getPreloadPaths(): array;

    /**
     * @return array|string[]
     */
    public function getPreloadFiles(): array;

    /**
     * @return array|string[]
     */
    public function getPreloadExtensions(): array;
}
