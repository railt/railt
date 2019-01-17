<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Config;

use Railt\Discovery\Discovery;

/**
 * Class Composer
 */
class Composer implements ConfigurationInterface
{
    /**
     * @var Discovery
     */
    private $discovery;

    /**
     * Composer constructor.
     * @param Discovery $discovery
     */
    public function __construct(Discovery $discovery)
    {
        $this->discovery = $discovery;
    }

    /**
     * @return \Railt\Foundation\Config\ConfigurationInterface
     * @throws \LogicException
     */
    public static function fromDiscovery(): ConfigurationInterface
    {
        return new static(Discovery::auto());
    }

    /**
     * @return iterable
     * @throws \InvalidArgumentException
     */
    public function getExtensions(): iterable
    {
        return (array)$this->discovery->get('railt.extensions', []);
    }

    /**
     * @return iterable
     * @throws \InvalidArgumentException
     */
    public function getCommands(): iterable
    {
        return (array)$this->discovery->get('railt.commands', []);
    }
}
