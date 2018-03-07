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
use Railt\Routing\Store\Box;

/**
 * Interface Resolver
 */
interface Resolver
{
    /**
     * @param InputInterface $input
     * @param Route $route
     * @param null|Box $parent
     * @return mixed
     */
    public function call(InputInterface $input, Route $route, ?Box $parent);
}
