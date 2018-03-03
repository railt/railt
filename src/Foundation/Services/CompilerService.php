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
use Railt\SDL\Compiler;
use Railt\SDL\Schema\CompilerInterface;
use Railt\Storage\Persister;

/**
 * Class CompilerService
 */
final class CompilerService implements Service
{
    /**
     * @param ContainerInterface $container
     * @param bool $debug
     * @throws \Railt\SDL\Exceptions\CompilerException
     * @throws \OutOfBoundsException
     */
    public function register(ContainerInterface $container, bool $debug): void
    {
        if (! $container->has(CompilerInterface::class)) {
            $container->register(CompilerInterface::class, function(Persister $cache) {
                return new Compiler($cache);
            });

            $container->alias(CompilerInterface::class, Compiler::class);
        }
    }
}
