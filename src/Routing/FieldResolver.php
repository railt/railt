<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing;

use Railt\Container\ContainerInterface as Container;
use Railt\Http\InputInterface;
use Railt\Reflection\Contracts\Definitions\InterfaceDefinition;
use Railt\Reflection\Contracts\Definitions\UnionDefinition;
use Railt\Reflection\Contracts\Dependent\FieldDefinition;
use Railt\Routing\Contracts\RouterInterface;
use Railt\Routing\Resolvers\Factory;
use Railt\Routing\Resolvers\Resolver;
use Railt\Routing\Store\ObjectBox;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class FieldResolver
 */
class FieldResolver
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var Resolver
     */
    private $resolver;

    /**
     * @var Container
     */
    private $container;

    /**
     * FieldResolver constructor.
     * @param RouterInterface $router
     * @param EventDispatcherInterface $dispatcher
     * @param Container $container
     */
    public function __construct(RouterInterface $router, EventDispatcherInterface $dispatcher, Container $container)
    {
        $this->router    = $router;
        $this->resolver  = new Factory($dispatcher);
        $this->container = $container;
    }

    /**
     * @param InputInterface $input
     * @param ObjectBox $parent
     * @return array|mixed
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \TypeError
     */
    public function handle(InputInterface $input, ?ObjectBox $parent)
    {
        $type = $input->getFieldDefinition()->getTypeDefinition();

        switch (true) {
            case $type instanceof UnionDefinition:
            case $type instanceof InterfaceDefinition:
                return $this->handleGeneralType($input, $parent);
            default:
                return $this->handleObject($input, $parent);
        }
    }

    /**
     * @param InputInterface $input
     * @param null|ObjectBox $parent
     * @return mixed|null
     */
    private function handleGeneralType(InputInterface $input, ?ObjectBox $parent)
    {
        $result = [];

        foreach ($this->routes($input) as $route) {
            if ($response = $this->call($route, $input, $parent)) {
                if (! $input->getFieldDefinition()->isList()) {
                    return $response;
                }

                if ($response instanceof \Traversable) {
                    $response = \iterator_to_array($response);
                }

                $result = \array_merge($result, $response);
            }
        }

        return $result;
    }

    /**
     * @param InputInterface $input
     * @param null|ObjectBox $parent
     * @return mixed
     */
    private function handleObject(InputInterface $input, ?ObjectBox $parent)
    {
        $response = null;

        foreach ($this->routes($input) as $route) {
            $response = $this->call($route, $input, $parent);

            if ($response) {
                break;
            }
        }

        return $response;
    }

    /**
     * @param InputInterface $input
     * @return \Traversable|Route[]
     */
    private function routes(InputInterface $input): \Traversable
    {
        $field  = $input->getFieldDefinition();
        $exists = false;

        foreach ($this->router->get($field) as $route) {
            if (! $route->matchOperation($input->getOperation())) {
                continue;
            }

            $exists = true;

            yield $route;
        }

        if (! $exists) {
            yield $this->getDefault($field);
        }
    }

    /**
     * @param InputInterface $input
     * @param Route $route
     * @param null|ObjectBox $parent
     * @return mixed
     * @throws \TypeError
     */
    private function call(Route $route, InputInterface $input, ?ObjectBox $parent)
    {
        return $this->resolver->call($route, $input, $parent);
    }

    /**
     * @param FieldDefinition $field
     * @return Route
     */
    private function getDefault(FieldDefinition $field): Route
    {
        return (new Route($this->container, $field))
            ->then(DefaultResolver::toClosure($this->container));
    }
}
