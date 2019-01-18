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
 * Class Composer
 * @deprecated Use Railt\Foundation\Config\Discovery instead.
 */
class Composer extends Discovery
{
    /**
     * @return ConfigurationInterface
     * @throws \LogicException
     */
    public static function fromDiscovery(): ConfigurationInterface
    {
        return parent::auto();
    }
}
