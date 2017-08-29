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
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class Proxy
 * @package Railt\Container
 */
class Proxy implements ContainerInterface
{
    /**
     * @var ContainerInterface
     */
    private $front;

    /**
     * @var ContainerInterface
     */
    private $back;

    /**
     * Proxy constructor.
     * @param ContainerInterface $front
     * @param ContainerInterface $back
     */
    public function __construct(ContainerInterface $front, ContainerInterface $back)
    {
        $this->front = $front;
        $this->back = $back;
    }

    /**
     * @param string $id
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function get($id)
    {
        return $this->front->has($id) ? $this->front->get($id) : $this->back->get($id);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has($id): bool
    {
        return $this->front->has($id) || $this->back->has($id);
    }
}
