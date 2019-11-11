<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Extension;

use Railt\Dumper\Facade;
use Railt\SDL\TypeSystemServiceExtension;
use Railt\Discovery\DiscoveryServiceExtension;
use Railt\Contracts\Config\RepositoryInterface;
use Railt\Contracts\Container\ContainerInterface;
use Railt\HttpFactory\HttpFactoryServiceExtension;
use Railt\Container\Exception\ContainerInvocationException;
use Railt\Foundation\Extension\Exception\ExtensionException;
use Railt\Foundation\Extension\ConfigurationRepository as ExtensionRepository;
use Railt\Foundation\Extension\RepositoryInterface as ExtensionRepositoryInterface;

/**
 * @mixin ExtendableInterface
 */
trait ExtensionsTrait
{
    /**
     * @var ExtensionRepositoryInterface
     */
    protected ExtensionRepositoryInterface $extensions;

    /**
     * @var array|string[]
     */
    private array $defaultExtensions = [
        DiscoveryServiceExtension::class,
        TypeSystemServiceExtension::class,
        HttpFactoryServiceExtension::class,
    ];

    /**
     * @return ExtensionRepositoryInterface
     */
    public function extensions(): ExtensionRepositoryInterface
    {
        return $this->extensions;
    }

    /**
     * @param ContainerInterface $app
     * @param RepositoryInterface $config
     * @return void
     * @throws ContainerInvocationException
     * @throws ExtensionException
     */
    protected function bootExtendableTrait(ContainerInterface $app, RepositoryInterface $config): void
    {
        $this->extensions = new ExtensionRepository($app, $config);

        foreach ($this->defaultExtensions as $extension) {
            if (! \class_exists($extension)) {
                $message = 'Can not load kernel console command %s';
                \trigger_error(\sprintf($message, $extension), \E_USER_WARNING);

                continue;
            }

            $this->extend($extension);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function extend($extension): void
    {
        \assert(\is_subclass_of($extension, ExtensionInterface::class), Facade::dump($extension));

        $this->extensions->add($extension);
    }
}
