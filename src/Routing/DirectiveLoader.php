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
use Railt\SDL\Contracts\Document;
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
     * @throws \ReflectionException
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
     * @throws \ReflectionException
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
     * @return callable
     * @throws \ReflectionException
     */
    private function createAction(FieldDefinition $field, DirectiveInvocation $directive): callable
    {
        [$class, $method] = $this->getAction($field->getDocument(), $directive);

        //
        // If method is static
        //
        if ((new \ReflectionMethod($class, $method))->isStatic()) {
            return [$class, $method];
        }

        //
        // If method an instance of controller
        //
        $instance = $this->createController($class, $this->isSingleton($directive));

        return [$instance, $method];
    }

    /**
     * @param Document $document
     * @param DirectiveInvocation $directive
     * @return array
     */
    private function getAction(Document $document, DirectiveInvocation $directive): array
    {
        [$action, $line] = [
            $directive->getPassedArgument('action'),
            $directive->getDeclarationLine(),
        ];

        return $this->loader->action($document, $action, $line);
    }

    /**
     * @param string $controller
     * @param bool $singleton
     * @return object
     */
    private function createController(string $controller, bool $singleton)
    {
        if ($singleton && isset($this->controllers[$controller])) {
            return $this->controllers[$controller];
        }

        return $this->controllers[$controller] = $this->container->make($controller);
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
