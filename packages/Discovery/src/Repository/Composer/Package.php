<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Discovery\Repository\Composer;

use Composer\Composer;
use Railt\Discovery\Parser\Factory;
use Composer\Package\RootPackageInterface;
use Railt\Discovery\Repository\PackageInterface;
use Composer\Package\PackageInterface as ComposerPackageInterface;

/**
 * Class Package
 */
class Package implements PackageInterface
{
    /**
     * @var Composer
     */
    private Composer $composer;

    /**
     * @var ComposerPackageInterface
     */
    private ComposerPackageInterface $package;

    /**
     * @var array
     */
    private array $extra;

    /**
     * Package constructor.
     *
     * @param Composer $composer
     * @param ComposerPackageInterface $package
     */
    public function __construct(Composer $composer, ComposerPackageInterface $package)
    {
        $this->composer = $composer;
        $this->package = $package;

        $this->extra = Factory::create($composer, $package)->bypass($package->getExtra());
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->package->getName();
    }

    /**
     * @param string|null $section
     * @return array
     */
    public function getExtra(string $section = null): array
    {
        if ($section === null) {
            return $this->extra;
        }

        return $this->extra[$section] ?? [];
    }

    /**
     * @return string
     */
    public function getDirectory(): string
    {
        if ($this->package instanceof RootPackageInterface) {
            $config = $this->composer->getConfig();
            $source = $config->getConfigSource();

            return \dirname($source->getName());
        }

        $installer = $this->composer->getInstallationManager();

        return $installer->getInstallPath($this->package);
    }
}
