<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Application;

use Cache\Adapter\PHPArray\ArrayCachePool;
use Psr\SimpleCache\CacheInterface;
use Railt\Foundation\Application;
use Railt\Foundation\Extension\Extension;
use Railt\Foundation\Extension\Status;

/**
 * Class CacheExtension
 */
class CacheExtension extends Extension
{
    /**
     * @var int
     */
    protected const DEFAULT_CACHE_POOL_SIZE = 0x00ff;

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Cache';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Provides cache driver.';
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return Application::VERSION;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return Status::STABLE;
    }

    /**
     * @return void
     * @throws \Railt\Container\Exception\ContainerInvocationException
     */
    public function register(): void
    {
        $this->registerIfNotRegistered(CacheInterface::class, function () {
            return new ArrayCachePool(static::DEFAULT_CACHE_POOL_SIZE);
        });
    }
}
