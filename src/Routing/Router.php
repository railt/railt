<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Routing;

use Serafim\Railgun\Exceptions\RuntimeException;
use Serafim\Railgun\Runtime\RequestInterface;
use Serafim\Railgun\Runtime\Resolvable;

/**
 * Class Router
 * @package Serafim\Railgun\Routing
 */
abstract class Router
{
    /**
     * @param RequestInterface $request
     * @return iterable
     */
    abstract public function resolve(RequestInterface $request): iterable;

    /**
     * @param string $pattern
     * @return Route
     */
    public function route(string $pattern): Route
    {
        return Route::new($pattern);
    }

    /**
     * @param string $class
     * @return \Closure
     */
    public function belongsTo(string $class): \Closure
    {
        return function (RequestInterface $request) use ($class) {
            $instance = new $class();

            if ($instance instanceof Resolvable) {
                return $instance->resolve($request);
            }

            throw RuntimeException::new('%s::class must be instance of %s', $class, Resolvable::class);
        };
    }
}
