<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\HttpFactory;

use Railt\Foundation\Extension\Extension;
use Railt\Foundation\Extension\Status;
use Railt\Http\RequestInterface;
use Railt\HttpFactory\Provider\ProviderInterface;

/**
 * Class HttpFactoryServiceExtension
 */
class HttpFactoryServiceExtension extends Extension
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->register(FactoryInterface::class, static function () {
            return new Factory();
        });

        $this->app->register(RequestInterface::class, function (FactoryInterface $factory) {
            if ($this->app->has(ProviderInterface::class)) {
                return $factory->create($this->app->make(ProviderInterface::class));
            }

            return $factory->fromGlobals();
        });
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'Http Factory';
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription(): string
    {
        return 'Provides GraphQL HTTP Factory Kernel services';
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
