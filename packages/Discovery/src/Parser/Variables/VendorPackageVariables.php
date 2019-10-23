<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Discovery\Parser\Variables;

use Composer\Composer;
use Composer\Package\PackageInterface;
use Composer\Package\RootPackageInterface;

/**
 * Class VendorPackageVariables
 */
class VendorPackageVariables extends PackageVariables
{
    /**
     * @var Composer
     */
    private Composer $composer;

    /**
     * VendorPackageVariables constructor.
     *
     * @param Composer $composer
     * @param PackageInterface $package
     */
    public function __construct(Composer $composer, PackageInterface $package)
    {
        $this->composer = $composer;

        parent::__construct($package);
    }

    /**
     * @return string
     */
    protected function prefix(): string
    {
        return 'package';
    }

    /**
     * @return string
     */
    protected function getDirectory(): string
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
