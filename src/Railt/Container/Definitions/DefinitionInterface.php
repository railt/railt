<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Container\Definitions;

use Railt\Container\ContainerInterface;

/**
 * Interface DefinitionInterface
 */
interface DefinitionInterface
{
    /**
     * DefinitionInterface constructor.
     * @param ContainerInterface $container
     * @param $target
     */
    public function __construct(ContainerInterface $container, $target);

    /**
     * @return mixed
     */
    public function resolve();
}
