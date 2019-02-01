<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Config;

use Illuminate\Support\Str;

/**
 * Class Config
 */
class Config implements ConfigurationInterface
{
    /**
     * @var array
     */
    private $extensions;

    /**
     * @var array
     */
    private $commands;

    /**
     * @var array
     */
    private $autoload;

    /**
     * Config constructor.
     *
     * @param iterable|string[] $extensions
     * @param iterable|string[] $commands
     * @param iterable|string[] $autoload
     */
    public function __construct(
        iterable $extensions = [],
        iterable $commands = [],
        iterable $autoload = []
    ) {
        $this->extensions = \iterable_to_array($extensions, false);
        $this->commands   = \iterable_to_array($commands, false);
        $this->autoload   = \iterable_to_array($autoload, false);
    }

    /**
     * @return iterable|string[]
     */
    public function getExtensions(): iterable
    {
        return $this->extensions;
    }

    /**
     * @return iterable|string[]
     */
    public function getCommands(): iterable
    {
        return $this->commands;
    }

    /**
     * @return iterable|string[]
     */
    public function getAutoloadPaths(): iterable
    {
        return $this->autoload;
    }
}
