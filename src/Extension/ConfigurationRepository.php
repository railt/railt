<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Foundation\Extension;

use Railt\Observer\ObservableInterface;
use Railt\Container\ContainerInterface;
use Railt\Container\Exception\ContainerInvocationException;
use Railt\Foundation\Extension\Exception\ExtensionException;
use Railt\Config\RepositoryInterface as ConfigRepositoryInterface;

/**
 * Class ConfigurationRepository
 */
class ConfigurationRepository extends Repository
{
    /**
     * ConfigurationRepository constructor.
     *
     * @param ContainerInterface $app
     * @param ConfigRepositoryInterface $config
     * @throws ContainerInvocationException
     * @throws ExtensionException
     */
    public function __construct(ContainerInterface $app, ConfigRepositoryInterface $config)
    {
        parent::__construct($app);

        if ($config instanceof ObservableInterface) {
            $config->subscribe(fn () => $this->load($config));
        }

        $this->load($config);
    }

    /**
     * @param ConfigRepositoryInterface $config
     * @return void
     * @throws ContainerInvocationException
     * @throws ExtensionException
     */
    private function load(ConfigRepositoryInterface $config): void
    {
        $extensions = (array)$config->get($this->getConfigurationKey(), []);

        foreach ($extensions as $extension) {
            if (! $this->isRegistered($extension)) {
                $this->register($extension);
            }
        }
    }

    /**
     * @return string
     */
    protected function getConfigurationKey(): string
    {
        return 'extensions';
    }
}
