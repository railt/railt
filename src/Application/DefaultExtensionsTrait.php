<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Application;

/**
 * Trait DefaultExtensionsTrait
 */
trait DefaultExtensionsTrait
{
    /**
     * @var array|string[]
     */
    private array $defaultExtensions = [
        \Railt\Discovery\DiscoveryConfigurationExtension::class,
        \Railt\TypeSystem\CompilerExtension::class,
    ];

    /**
     * @return void
     */
    protected function bootDefaultExtensionsTrait(): void
    {
        foreach ($this->defaultExtensions as $extension) {
            $this->extend($extension);
        }
    }
}
