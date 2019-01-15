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
    /**
     * @var array
     */
    private $extensions;

    /**
     * @var array
     */
    private $commands;

    /**
     * Config constructor.
     * @param iterable $extensions
     * @param iterable $commands
     */
    public function __construct(iterable $extensions = [], iterable $commands = [])
    {
        $this->extensions = $this->toArray($extensions);
        $this->commands   = $this->toArray($commands);
    }

    /**
     * @param iterable $items
     * @return array
     */
    private function toArray(iterable $items): array
    {
        return $items instanceof \Traversable ? \iterator_to_array($items) : $items;
    }

    /**
     * @return iterable
     */
    public function getExtensions(): iterable
    {
        return $this->extensions;
    }

    /**
     * @return iterable
     */
    public function getCommands(): iterable
    {
        return $this->commands;
    }
}
