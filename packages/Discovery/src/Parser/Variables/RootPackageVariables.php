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

/**
 * Class RootPackageVariables
 */
class RootPackageVariables extends PackageVariables
{
    /**
     * @var Composer
     */
    private Composer $composer;

    /**
     * RootPackageVariables constructor.
     *
     * @param Composer $composer
     */
    public function __construct(Composer $composer)
    {
        $this->composer = $composer;

        parent::__construct($composer->getPackage());
    }

    /**
     * @return string
     */
    protected function prefix(): string
    {
        return 'app';
    }

    /**
     * @return string
     */
    protected function getDirectory(): string
    {
        $config = $this->composer->getConfig();
        $source = $config->getConfigSource();

        return \dirname($source->getName());
    }
}
