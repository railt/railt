<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Extension;

use Railt\Config\MutableRepositoryInterface as MutableConfigRepositoryInterface;
use Railt\Config\RepositoryInterface as ConfigRepositoryInterface;
use Railt\Console\RepositoryInterface as CommandsRepositoryInterface;
use Railt\Container\ContainerInterface;
use Railt\Extension\Extension;
use Railt\Extension\RepositoryInterface as ExtensionsRepositoryInterface;
use Railt\Extension\Status;

/**
 * Class DefaultBindingsExtension
 */
class DefaultBindingsExtension extends Extension
{
    /**
     * @var string
     */
    public const APP_LOCATOR = '$app';

    /**
     * @var string
     */
    public const CONFIG_LOCATOR = '$config';

    /**
     * @var string
     */
    public const COMMANDS_LOCATOR = '$commands';

    /**
     * @var string
     */
    public const EXTENSIONS_LOCATOR = '$extensions';

    /**
     * @var array[]
     */
    private const ALIASES = [
        self::APP_LOCATOR => [
            ApplicationInterface::class,
            ContainerInterface::class,
        ],

        self::CONFIG_LOCATOR => [
            ConfigRepositoryInterface::class,
            MutableConfigRepositoryInterface::class,
        ],

        self::COMMANDS_LOCATOR => [
            CommandsRepositoryInterface::class,
        ],

        self::EXTENSIONS_LOCATOR => [
            ExtensionsRepositoryInterface::class,
        ],
    ];

    /**
     * @return void
     */
    public function register(): void
    {
        foreach (self::ALIASES as $locator => $aliases) {
            $this->app->alias($locator, ...$aliases);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'Application';
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

    /**
     * {@inheritDoc}
     */
    public function getDescription(): string
    {
        return 'Binds interfaces of standard objects inside DI container';
    }
}
