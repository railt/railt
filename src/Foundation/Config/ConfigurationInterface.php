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
 * Interface ConfigurationInterface
 */
interface ConfigurationInterface
{
    /**
     * @return iterable|string[]
     */
    public function getExtensions(): iterable;

    /**
     * @return iterable|string[]
     */
    public function getCommands(): iterable;

    /**
     * @return iterable|string[]
     */
    public function getAutoloadPaths(): iterable;
}
