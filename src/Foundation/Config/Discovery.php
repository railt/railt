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
 */
class Discovery extends Config
{
    /**
     * @var string
     */
    public const KEY_COMMANDS = 'railt.commands';

    /**
     * @var string
     */
    public const KEY_EXTENSIONS = 'railt.extensions';

    /**
     * @var string
     */
    public const KEY_AUTOLOAD_PATHS = 'railt.autoload.paths';

    /**
     * @var string
     */
    public const KEY_AUTOLOAD_FILES = 'railt.autoload.files';

    /**
     * @var string
     */
    public const KEY_AUTOLOAD_EXTENSIONS = 'railt.autoload.extensions';

    /**
     * @var string
     */
    public const KEY_PRELOAD_PATHS = 'railt.preload.paths';

    /**
     * @var string
     */
    public const KEY_PRELOAD_FILES = 'railt.preload.files';

    /**
     * @var string
     */
    public const KEY_PRELOAD_EXTENSIONS = 'railt.preload.extensions';

    /**
     * Discovery constructor.
     *
     * @param RailtDiscovery $discovery
     * @throws \InvalidArgumentException
     */
    public function __construct(RailtDiscovery $discovery)
    {
        $this->bootAsArray($discovery, [
            self::KEY_COMMANDS            => [$this, 'withCommands'],
            self::KEY_EXTENSIONS          => [$this, 'withExtensions'],

            self::KEY_AUTOLOAD_EXTENSIONS => [$this, 'withAutoloadExtensions'],
            self::KEY_AUTOLOAD_PATHS      => [$this, 'withAutoloadPaths'],
            self::KEY_AUTOLOAD_FILES      => [$this, 'withAutoloadFiles'],

            self::KEY_PRELOAD_PATHS       => [$this, 'withPreloadPaths'],
            self::KEY_PRELOAD_FILES       => [$this, 'withPreloadFiles'],
            self::KEY_PRELOAD_EXTENSIONS  => [$this, 'withPreloadExtensions'],
        ]);
    }

    /**
     * @param RailtDiscovery $discovery
     * @param array|callable[] $mappings
     * @throws \InvalidArgumentException
     */
    private function bootAsArray(RailtDiscovery $discovery, array $mappings)
    {
        foreach ($mappings as $key => $callable) {
            $callable((array)$discovery->get($key, []));
        }
    }

    /**
     * @param array $paths
     * @return Discovery
     * @throws \LogicException
     */
    public static function auto(array $paths = []): self
    {
        return new static(RailtDiscovery::auto($paths));
    }
}
