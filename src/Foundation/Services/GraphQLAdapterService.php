<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Services;

use Railt\Adapters\AdapterInterface;
use Railt\Adapters\Webonyx\Adapter;
use Railt\Container\ContainerInterface;

/**
 * Class GraphQLAdapterService
 */
final class GraphQLAdapterService implements Service
{
    private const ADAPTERS = [
        Adapter::class,
    ];

    /**
     * @param ContainerInterface $container
     * @param bool $debug
     * @throws \InvalidArgumentException
     */
    public function register(ContainerInterface $container, bool $debug): void
    {
        if (! $container->has(AdapterInterface::class)) {
            $container->register(AdapterInterface::class, function(ContainerInterface $container) use ($debug) {
                $class = $this->factory();

                return new $class($container, $debug);
            });
        }
    }

    /**
     * @return string|AdapterInterface
     * @throws \InvalidArgumentException
     */
    private function factory(): string
    {
        foreach (self::ADAPTERS as $adapter) {
            if (\class_exists($adapter)) {
                return $adapter;
            }
        }

        throw new \InvalidArgumentException('Could not resolve available adapter');
    }
}
