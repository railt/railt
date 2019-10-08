<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Discovery;

use Railt\Config\MutableRepositoryInterface;
use Railt\Config\Repository;
use Railt\Foundation\Extension\Extension;
use Railt\Foundation\Extension\Status;

/**
 * Class DiscoveryConfigurationExtension
 */
class DiscoveryConfigurationExtension extends Extension
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
        $config->merge(new Repository(Manifest::get(self::MANIFEST_KEY)));
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
