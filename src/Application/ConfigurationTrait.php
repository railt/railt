<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Foundation\Application;

use Railt\Dumper\Facade;
use Railt\Config\MutableRepository as ConfigRepository;
use Railt\Config\RepositoryInterface as ConfigRepositoryInterface;

/**
 * Trait ConfigurationTrait
 */
trait ConfigurationTrait
{
    /**
     * @var ConfigRepositoryInterface
     */
    protected ConfigRepositoryInterface $config;

    /**
     * @param array|ConfigRepositoryInterface|null $config
     * @return void
     */
    protected function bootConfigurationTrait($config = null): void
    {
        $this->config = $this->createConfiguration($config);
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function config(string $key, $default = null)
    {
        return $this->config->get($key, $default);
    }

    /**
     * @param array|ConfigRepositoryInterface|null $config
     * @return ConfigRepositoryInterface
     */
    private function createConfiguration($config): ConfigRepositoryInterface
    {
        switch (true) {
            case $config === null:
                return new ConfigRepository();
                break;

            case $config instanceof ConfigRepositoryInterface:
                $repository = new ConfigRepository();
                $repository->merge($config);

                return $repository;

            case \is_array($config):
                return new ConfigRepository($config);
                break;

            default:
                throw new \InvalidArgumentException($this->configurationErrorMessage($config));
        }
    }

    /**
     * @param mixed $config
     * @return string
     */
    private function configurationErrorMessage($config): string
    {
        $error = 'Configuration format should be an array or instanceof %s, but %s given';

        return \sprintf($error, ConfigRepositoryInterface::class, Facade::dump($config));
    }
}
