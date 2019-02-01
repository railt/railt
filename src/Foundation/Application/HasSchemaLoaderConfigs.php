<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Application;

use Railt\Foundation\Config\ConfigurationInterface;
use Railt\Foundation\Config\HasAutoloadingConfigs;
use Railt\Foundation\Config\HasPreloadingConfigs;

/**
 * Trait HasSchemaLoaderConfigs
 */
trait HasSchemaLoaderConfigs
{
    use HasAutoloadingConfigs;
    use HasPreloadingConfigs;

    /**
     * @param ConfigurationInterface $configs
     * @return void
     */
    protected function loadSchemaLoaderConfigsFrom(ConfigurationInterface $configs): void
    {
        $this->loadAutoloadingConfigsFrom($configs);
        $this->loadPreloadingConfigsFrom($configs);
    }
}
