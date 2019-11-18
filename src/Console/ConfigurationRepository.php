<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Console;

use Railt\Observer\ObservableInterface;
use Railt\Container\ContainerInterface;
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
     */
    private function load(ConfigRepositoryInterface $config): void
    {
        $commands = (array)$config->get($this->getConfigurationKey(), []);

        foreach ($commands as $command) {
            if (! $this->isRegistered($command)) {
                $this->register($command);
            }
        }
    }

    /**
     * @return string
     */
    protected function getConfigurationKey(): string
    {
        return 'commands';
    }
}
