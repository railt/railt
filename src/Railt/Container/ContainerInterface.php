<?php
/**
 * This file is part of railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Container;

use Psr\Container\ContainerInterface as PsrContainer;

/**
 * Interface ContainerInterface
 */
interface ContainerInterface extends PsrContainer, RegistrableInterface
{
    /**
     * @param callable $callable
     * @param array $params
     * @return mixed
     */
    public function call(callable $callable, array $params = []);
}
