<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Discovery;

use Railt\Config\Repository;
use Railt\Foundation\Extension\Status;
use Railt\Foundation\Extension\Extension;
use Railt\Config\MutableRepositoryInterface;

/**
 * Class DiscoveryServiceExtension
 */
class DiscoveryServiceExtension extends Extension
{
    /**
     * @var string
     */
    private const MANIFEST_KEY = 'railt';

    /**
     * @param MutableRepositoryInterface $config
     * @return void
     */
    public function register(MutableRepositoryInterface $config): void
    {
        $manifest = Manifest::get(self::MANIFEST_KEY, []);

        $config->merge(new Repository((array)$manifest));
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'Discovery';
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription(): string
    {
        return 'Loads configuration from composer.json file';
    }

    /**
     * {@inheritDoc}
     */
    public function getStatus(): string
    {
        return Status::STABLE;
    }

    /**
     * {@inheritDoc}
     */
    public function getVersion(): string
    {
        return $this->app->getVersion();
    }
}
