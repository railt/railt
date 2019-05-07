<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Extension\Routing;

use Railt\Http\InputInterface;
use Railt\Http\RequestInterface;

/**
 * Interface RouterInterface
 */
interface RouterInterface
{
    /**
     * @param RequestInterface $request
     * @param InputInterface $input
     * @return iterable|RouteInterface[]
     */
    public function resolve(RequestInterface $request, InputInterface $input): iterable;

    /**
     * @param RouteInterface $route
     * @return RouterInterface
     */
    public function add(RouteInterface $route): self;

    /**
     * @param callable|mixed $action
     * @return RouteInterface
     */
    public function create($action): RouteInterface;

    /**
     * @return iterable|RouteInterface[]
     */
    public function all(): iterable;
}
