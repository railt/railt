<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Application;

use Railt\Config;
use Railt\Foundation\Console;
use Railt\Foundation\Extension;
use Railt\Foundation\Application;
use Railt\Container\ContainerInterface;
use Railt\Foundation\ApplicationInterface;
use Psr\Container\ContainerInterface as PsrContainerInterface;

/**
 * Trait DefaultBindingsTrait
 */
trait DefaultBindingsTrait
{
    /**
     * @var array|array[]
     */
    private array $aliases = [
        Application::class          => [
            '$app',
            ApplicationInterface::class,
            ContainerInterface::class,
            PsrContainerInterface::class,
        ],
        Config\Repository::class    => [
            '$config',
            Config\RepositoryInterface::class,
        ],
        Console\Repository::class   => [
            Console\RepositoryInterface::class,
        ],
        Extension\Repository::class => [
            Extension\RepositoryInterface::class,
        ],
    ];

    /**
     * @return void
     */
    protected function bootDefaultBindingsTrait(): void
    {
        $this->registerServiceLocators();
        $this->registerServiceAliases();
    }

    /**
     * @return void
     */
    private function registerServiceLocators(): void
    {
        $locators = [
            Application::class          => fn () => $this,
            Config\Repository::class    => fn () => $this->config,
            Console\Repository::class   => fn () => $this->commands,
            Extension\Repository::class => fn () => $this->extensions,
        ];

        foreach ($locators as $name => $cb) {
            $this->register($name, $cb);
        }
    }

    /**
     * @return void
     */
    private function registerServiceAliases(): void
    {
        foreach ($this->aliases as $concrete => $aliases) {
            $this->alias($concrete, ...$aliases);
        }
    }
}
