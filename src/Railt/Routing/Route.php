<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing;

use Railt\Http\Request;
use Railt\Routing\Contracts\RouteInterface;
use Railt\Routing\Contracts\RouterInterface;

/**
 * Class Route
 */
class Route implements RouteInterface
{
    public const REQUEST_TYPE_QUERY = 'query';
    public const REQUEST_TYPE_MUTATION = 'mutation';
    public const REQUEST_TYPE_SUBSCRIPTION = 'subscription';

    /**
     * Query type ("query", "mutation" or "subscription")
     */
    private const METADATA_REQUEST_TYPE = 'type';

    /**
     * Metadata middleware list
     */
    private const METADATA_MIDDLEWARE = 'middleware';

    /**
     * @var RouterInterface|Router
     */
    private $parent;

    /**
     * @var string
     */
    private $route;

    /**
     * @var array
     */
    private $metadata = [];

    /**
     * @var string|callable
     */
    private $action;

    /**
     * @var StringableAction|null
     */
    private $parser;

    /**
     * Route constructor.
     * @param RouterInterface $parent
     * @param string $route
     * @param string|callable $action
     */
    public function __construct(RouterInterface $parent, string $route, $action)
    {
        $this->parent = $parent;
        $this->route = $route;
        $this->action = $action;
    }

    /**
     * @param string $key
     * @return Route
     */
    private function bootMetadata(string $key): Route
    {
        if (!array_key_exists($key, $this->metadata)) {
            $this->metadata[$key] = [];
        }

        return $this;
    }

    /**
     * @param string $key
     * @param string[] ...$values
     * @return Route
     */
    private function addMetadata(string $key, string ...$values): Route
    {
        $this->bootMetadata($key);

        foreach ($values as $value) {
            $this->metadata[$key][] = $value;
        }

        $this->metadata[$key] = array_unique($this->metadata[$key]);

        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     * @return bool
     */
    private function hasMetadata(string $key, string $value): bool
    {
        return in_array($value, $this->getMetadata($key), true);
    }

    /**
     * @param string $key
     * @return array
     */
    private function getMetadata(string $key): array
    {
        $this->bootMetadata($key);

        return $this->metadata[$key];
    }

    /**
     * @param string[] ...$middleware
     * @return RouteInterface
     * @throws \LogicException
     */
    public function middleware(string ...$middleware): RouteInterface
    {
        return $this->addMetadata(self::METADATA_MIDDLEWARE, ...$middleware);
    }

    /**
     * @return array
     */
    public function getMiddleware(): array
    {
        return $this->getMetadata(self::METADATA_MIDDLEWARE);
    }

    /**
     * @param string $middleware
     * @return bool
     */
    public function hasMiddleware(string $middleware): bool
    {
        return $this->hasMetadata(self::METADATA_MIDDLEWARE, $middleware);
    }

    /**
     * @param string[] ...$queryTypes
     * @return RouteInterface
     */
    public function type(string ...$queryTypes): RouteInterface
    {
        return $this->addMetadata(self::METADATA_REQUEST_TYPE, ...$queryTypes);
    }

    /**
     * @return array
     */
    public function getTypes(): array
    {
        return $this->getMetadata(self::METADATA_REQUEST_TYPE);
    }

    /**
     * @param string $requestType
     * @return bool
     */
    public function hasType(string $requestType): bool
    {
        return $this->hasMetadata(self::METADATA_REQUEST_TYPE, $requestType);
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @return string
     */
    public function getPattern(): string
    {
        return sprintf('/^%s$/isu', preg_quote($this->route, '/'));
    }

    /**
     * @param string $action
     * @return bool
     * @throws \LogicException
     */
    public function match(string $action): bool
    {
        return preg_match($this->getPattern(), $action) > 0;
    }

    /**
     * @param array $params
     * @return mixed
     * @throws \InvalidArgumentException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function call(array $params = [])
    {
        $container = $this->parent->getContainer();
        $callable  = $this->action;

        if (!is_callable($callable) && is_string($callable)) {
            if ($this->parser === null) {
                $this->parser = new StringableAction($container);
            }

            $callable = $this->parser->toCallable($callable, $this->parent->getNamespaces());
        }

        return $container->call($callable, $params);
    }

    /**
     * @return RouterInterface
     */
    public function getRouter(): RouterInterface
    {
        return $this->parent;
    }
}
