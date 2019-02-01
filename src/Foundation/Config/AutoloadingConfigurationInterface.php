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
 * Interface AutoloadingConfigurationInterface
 */
interface AutoloadingConfigurationInterface
{
    /**
     * @return array|string[]
     */
    public function getAutoloadPaths(): array;

    /**
     * @return array|string[]
     */
    public function getAutoloadFiles(): array;

    /**
     * @return array|string[]
     */
    public function getAutoloadExtensions(): array;
}
