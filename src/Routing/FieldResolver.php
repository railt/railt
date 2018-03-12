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
        $field = $input->getFieldDefinition();

        foreach ($this->router->get($field) as $route) {
            if (! $route->matchOperation($input->getOperation())) {
                continue;
            }

            return $this->call($input, $route, $parent);
        }

        return $this->call($input, $this->getDefault($field), $parent);
    }

    /**
     * @param InputInterface $input
     * @param Route $route
     * @param null|ObjectBox $parent
     * @return mixed
     * @throws \TypeError
     */
    private function call(InputInterface $input, Route $route, ?ObjectBox $parent)
    {
        return $this->resolver->call($input, $route, $parent);
    }

    /**
     * @param FieldDefinition $field
     * @return Route
     */
    private function getDefault(FieldDefinition $field): Route
    {
        return (new Route($this->container, $field))->then(DefaultResolver::toClosure($this->container));
    }
}
