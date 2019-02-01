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
 * Trait HasAutoloadingConfigs
 * @mixin AutoloadingConfigurationInterface
 */
trait HasAutoloadingConfigs
{
    /**
     * @var array|string[]
     */
    protected $autoloadPaths = [];

    /**
     * @var array|string[]
     */
    protected $autoloadFiles = [];

    /**
     * @var array|string[]
     */
    protected $autoloadExtensions = [
        '.graphqls',
        '.graphql',
    ];

    /**
     * @param iterable|string[] $files
     * @return HasAutoloadingConfigs|$this
     */
    public function withAutoloadFiles(iterable $files): self
    {
        $files = \iterable_to_array($files, false);

        $this->autoloadFiles = \array_unique(\array_merge($this->autoloadFiles, $files));

        return $this;
    }

    /**
     * @param iterable|string[] $paths
     * @return HasAutoloadingConfigs|$this
     */
    public function withAutoloadPaths(array $paths): self
    {
        $paths = \iterable_to_array($paths, false);

        $this->autoloadPaths = \array_unique(\array_merge($this->autoloadPaths, $paths));

        return $this;
    }

    /**
     * @param iterable|string[] $extensions
     * @return HasAutoloadingConfigs|$this
     */
    public function withAutoloadExtensions(array $extensions): self
    {
        $extensions = \iterable_to_array($extensions, false);

        $this->autoloadExtensions = \array_unique(\array_merge($this->autoloadExtensions, $extensions));

        return $this;
    }

    /**
     * @return array|string[]
     */
    public function getAutoloadPaths(): array
    {
        return $this->autoloadPaths;
    }

    /**
     * @return array|string[]
     */
    public function getAutoloadFiles(): array
    {
        return $this->autoloadFiles;
    }

    /**
     * @return array|string[]
     */
    public function getAutoloadExtensions(): array
    {
        return $this->autoloadExtensions;
    }

    /**
     * @param AutoloadingConfigurationInterface $configs
     */
    protected function loadAutoloadingConfigsFrom(AutoloadingConfigurationInterface $configs): void
    {
        $this->withAutoloadFiles($configs->getAutoloadFiles());
        $this->withAutoloadPaths($configs->getAutoloadPaths());
        $this->withAutoloadExtensions($configs->getAutoloadExtensions());
    }
}
