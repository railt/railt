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
 * Class Config
 */
class Config implements ConfigurationInterface
{
    use HasPreloadingConfigs;
    use HasAutoloadingConfigs;

    /**
     * @var array|string[]
     */
    private $extensions = [];

    /**
     * @var array|string[]
     */
    private $commands = [];

    /**
     * @return array|string[]
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }

    /**
     * @param iterable|string[] $extensions
     * @return Config
     */
    public function withExtensions(iterable $extensions): self
    {
        $extensions = \iterable_to_array($extensions, false);

        $this->extensions = \array_unique(\array_merge($this->extensions, $extensions));

        return $this;
    }

    /**
     * @return array|string[]
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    /**
     * @param iterable|string[] $commands
     * @return Config
     */
    public function withCommands(iterable $commands): self
    {
        $commands = \iterable_to_array($commands, false);

        $this->commands = \array_unique(\array_merge($this->commands, $commands));

        return $this;
    }
}
