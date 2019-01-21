<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing;

use Railt\ClassLoader\ClassLoaderInterface;
use Railt\Container\ContainerInterface;
use Railt\SDL\Contracts\Dependent\FieldDefinition;
use Railt\SDL\Contracts\Invocations\DirectiveInvocation;

/**
 * Class DirectiveLoader
 */
class DirectiveLoader
{
    /**
     * @var ClassLoaderInterface
     */
    private $loader;

    /**
     * @var array|object[]
     */
    private $controllers = [];

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * DirectiveLoader constructor.
     *
     * @param ContainerInterface $container
     * @param RouterInterface $router
     * @param ClassLoaderInterface $loader
     */
    public function __construct(ContainerInterface $container, RouterInterface $router, ClassLoaderInterface $loader)
    {
        $this->router = $router;
        $this->loader = $loader;
        $this->container = $container;
    }

    /**
     * @param FieldDefinition $field
     */
    public function load(FieldDefinition $field): void
    {
        foreach ($field->getDirectives('route') as $directive) {
            $this->createFromDirective($field, $directive);
        }
    }

    /**
     * @param FieldDefinition $field
     * @param DirectiveInvocation $directive
     * @return RouteInterface
     */
    private function createFromDirective(FieldDefinition $field, DirectiveInvocation $directive): RouteInterface
    {
        $route = $this->router->create($this->createAction($field, $directive));

        $route->whereType($field->getParent()->getName());
        $route->whereField($field->getName());

        if ($directive->getPassedArgument('on')) {
            $route->wherePreferType($directive->getPassedArgument('on'));
        }

        return $route;
    }

    /**
     * @param FieldDefinition $field
     * @param DirectiveInvocation $directive
     * @return string|mixed
     */
    private function createAction(FieldDefinition $field, DirectiveInvocation $directive)
    {
        //
        // Action string in any allowed callable format.
        //
        $action = $directive->getPassedArgument('action');

        //
        // Extract FQN
        //
        $fqn = $this->loader->find($field->getDocument(), $action);

        //
        // Bind class
        //
        if ($this->isSingleton($directive)) {
            $chunks = \explode('@', $fqn);
            $class = \reset($chunks);

            $this->container->instance($class, $this->container->make($class));
        }

        return $fqn;
    }

    /**
     * @param DirectiveInvocation $directive
     * @return bool
     */
    private function isSingleton(DirectiveInvocation $directive): bool
    {
        return (bool)$directive->getPassedArgument('singleton');
    }
}
