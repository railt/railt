<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Config;

use Railt\Discovery\Discovery as RailtDiscovery;

/**
 * Class Discovery
 * @method static self|$this auto()
 */
class Discovery extends RailtDiscovery implements ConfigurationInterface
{
    /**
     * @return iterable|string[]
     * @throws \InvalidArgumentException
     */
    public function getExtensions(): iterable
    {
        return (array)$this->get('railt.extensions', []);
    }

    /**
     * @return iterable|string[]
     * @throws \InvalidArgumentException
     */
    public function getCommands(): iterable
    {
        return (array)$this->get('railt.commands', []);
    }

    /**
     * @return iterable|string[]
     * @throws \InvalidArgumentException
     */
    public function getAutoloadPaths(): iterable
    {
        return (array)$this->get('railt.autoload', []);
    }
}
