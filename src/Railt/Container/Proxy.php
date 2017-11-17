<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Container;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class Proxy
 */
class Proxy implements ContainerInterface
{
    /**
     * @var ContainerInterface
     */
    private $master;

    /**
     * @var ContainerInterface
     */
    private $slave;

    /**
     * Proxy constructor.
     * @param ContainerInterface $master
     * @param ContainerInterface $slave
     */
    public function __construct(ContainerInterface $master, ContainerInterface $slave)
    {
        $this->master = $master;
        $this->slave  = $slave;
    }

    /**
     * @param callable|string $callable
     * @param array $params
     * @return mixed
     */
    public function call(callable $callable, array $params = [])
    {
        return $this->master->call($callable, $params);
    }

    /**
     * @param string $id
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function get($id)
    {
        if ($this->master->has($id)) {
            return $this->master->get($id);
        }

        return $this->slave->get($id);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has($id): bool
    {
        return $this->master->has($id) || $this->slave->has($id);
    }
}
