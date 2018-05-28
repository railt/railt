<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing\Resolvers;

use Railt\Http\InputInterface;
use Railt\Routing\Route;
use Railt\Routing\Store\ObjectBox;

/**
 * Interface Resolver
 */
interface Resolver
{
    /**
     * @param Route $route
     * @param InputInterface $input
     * @param null|ObjectBox $parent
     * @return mixed
     */
    public function call(Route $route, InputInterface $input, ?ObjectBox $parent);
}
