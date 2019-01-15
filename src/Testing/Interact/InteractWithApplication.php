<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Testing\Interact;

use Psr\Container\ContainerInterface;
use Railt\Discovery\Discovery;
use Railt\Foundation\Application;
use Railt\Foundation\ApplicationInterface;
use Railt\Foundation\Config\Composer;

/**
 * Trait InteractWithApplication
 */
trait InteractWithApplication
{
    /**
     * @param string $name
     * @param mixed|null $default
     * @return mixed
     */
    abstract protected function env(string $name, $default = null);

    /**
     * @param ContainerInterface|null $container
     * @return ApplicationInterface
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    protected function app(ContainerInterface $container = null): ApplicationInterface
    {
        $app = new Application($this->isDebug(), $container);

        $app->configure(Composer::fromDiscovery());

        return $app;
    }

    /**
     * @return bool
     */
    protected function isDebug(): bool
    {
        return (bool)$this->env('RAILT_DEBUG', true);
    }

    /**
     * @return Discovery
     * @throws \LogicException
     */
    protected function getDiscovery(): Discovery
    {
        return Composer::getDiscovery();
    }
}
