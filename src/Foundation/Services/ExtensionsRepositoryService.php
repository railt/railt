<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Services;

use Railt\Container\ContainerInterface;
use Railt\Foundation\Extensions\Repository;

/**
 * Class ExtensionsRepositoryService
 */
final class ExtensionsRepositoryService implements Service
{
    /**
     * @param ContainerInterface $container
     * @param bool $debug
     */
    public function register(ContainerInterface $container, bool $debug): void
    {
        if (! $container->has(Repository::class)) {
            $container->register(Repository::class, function(ContainerInterface $container) {
                return new Repository($container);
            });
        }
    }
}
