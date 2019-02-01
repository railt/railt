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
 * Trait HasPreloadingConfigs
 * @mixin PreloadingConfigurationInterface
 */
trait HasPreloadingConfigs
{
    /**
     * @var array|string[]
     */
    protected $preloadPaths = [];

    /**
     * @var array|string[]
     */
    protected $preloadFiles = [];

    /**
     * @var array|string[]
     */
    protected $preloadExtensions = [
        '.graphqls',
        '.graphql',
    ];

    /**
     * @param iterable|string[] $files
     * @return PreloadingConfigurationInterface|$this
     */
    public function withPreloadFiles(array $files): self
    {
        $files = \iterable_to_array($files, false);

        $this->preloadFiles = \array_unique(\array_merge($this->preloadFiles, $files));

        return $this;
    }

    /**
     * @param iterable|string[] $paths
     * @return PreloadingConfigurationInterface|$this
     */
    public function withPreloadPaths(array $paths): self
    {
        $paths = \iterable_to_array($paths, false);

        $this->preloadPaths = \array_unique(\array_merge($this->preloadPaths, $paths));

        return $this;
    }

    /**
     * @param iterable|string[] $extensions
     * @return PreloadingConfigurationInterface|$this
     */
    public function withPreloadExtensions(array $extensions): self
    {
        $extensions = \iterable_to_array($extensions, false);

        $this->preloadExtensions = \array_unique(\array_merge($this->preloadExtensions, $extensions));

        return $this;
    }

    /**
     * @return array|string[]
     */
    public function getPreloadPaths(): array
    {
        return $this->preloadPaths;
    }

    /**
     * @return array|string[]
     */
    public function getPreloadFiles(): array
    {
        return $this->preloadFiles;
    }

    /**
     * @return array|string[]
     */
    public function getPreloadExtensions(): array
    {
        return $this->preloadExtensions;
    }

    /**
     * @param PreloadingConfigurationInterface $configs
     */
    protected function loadPreloadingConfigsFrom(PreloadingConfigurationInterface $configs): void
    {
        $this->withPreloadFiles($configs->getPreloadFiles());
        $this->withPreloadPaths($configs->getPreloadPaths());
        $this->withPreloadExtensions($configs->getPreloadExtensions());
    }
}
