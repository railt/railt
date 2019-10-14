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
use Railt\Config\RepositoryInterface;
use Railt\Container\ContainerInterface;
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
     * @var array|string[]
     */
    private array $defaultExtensions = [
        \Railt\Discovery\DiscoveryServiceExtension::class,
        \Railt\TypeSystem\TypeSystemServiceExtension::class,
        \Railt\HttpFactory\HttpFactoryServiceExtension::class,
        \Railt\Http\HttpServiceExtension::class,
    ];

    /**
     * @var ExtensionRepositoryInterface
     */
    protected ExtensionRepositoryInterface $extensions;

    /**
     * {@inheritDoc}
     */
    public function extend($extension): void
    {
        \assert(\is_subclass_of($extension, ExtensionInterface::class), Facade::dump($extension));

        $this->extensions->add($extension);
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
            $this->extend($extension);
        }
    }
}
