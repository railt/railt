<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Webonyx;

use GraphQL\Type\Definition\ResolveInfo;
use Railt\Container\ContainerInterface;
use Railt\Http\RequestInterface;
use Railt\Reflection\Contracts\Definitions\ObjectDefinition;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Contracts\Dependent\FieldDefinition;
use Railt\Reflection\Contracts\Invocations\DirectiveInvocation;
use Railt\Routing\Contracts\InputInterface;
use Railt\Routing\Contracts\RouterInterface;
use Railt\Routing\GraphQL\RouteDirective;
use Railt\Routing\Route;

/**
 * Class FieldResolver
 */
class FieldResolver
{
    private const DIRECTIVE           = RouteDirective::DIRECTIVE_NAME;
    private const DIRECTIVE_ACTION    = RouteDirective\ActionArgument::ARGUMENT_NAME;
    private const DIRECTIVE_OPERATION = RouteDirective\OperationArgument::ARGUMENT_NAME;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * FieldResolver constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->router    = $container->make(RouterInterface::class);
    }

    /**
     * @param RequestInterface $request
     * @param FieldDefinition $field
     * @return \Closure|null
     */
    public function getCallback(RequestInterface $request, FieldDefinition $field): ?\Closure
    {
        /** @var ObjectDefinition $object */
        $object = $field->getParent();

        $this->loadRouteDirective($object, $field);


        if (! $this->router->has($object)) {
            return null;
        }

        /** @var Route $route */
        $route = $this->router->get($object);

        // TODO Match operation
        // $request->getOperation()

        // TODO Match method
        // $request->getMethod()

        return function ($parent, array $arguments = [], $ctx, ResolveInfo $info) use ($route, $field) {
            return $route->call([
                InputInterface::class => new Input($field, $info, $arguments, $parent),
                TypeDefinition::class => $field,
                'parent'              => $parent,
            ]);
        };
    }

    /**
     * @param ObjectDefinition $object
     * @param FieldDefinition $field
     * @return void
     */
    private function loadRouteDirective(ObjectDefinition $object, FieldDefinition $field): void
    {
        if (! $field->hasDirective(self::DIRECTIVE)) {
            return;
        }

        /** @var DirectiveInvocation $directive */
        $directive = $field->getDirective(self::DIRECTIVE);

        $urn = (string)$directive->getPassedArgument(self::DIRECTIVE_ACTION);

        $this->router->route($object, $field->getName())
            ->then($this->createCallback($urn));

        // TODO Add operations
        // TODO Add method
    }

    /**
     * @param string $urn
     * @return \Closure
     */
    private function createCallback(string $urn): \Closure
    {
        [$controller, $action] = \explode('@', $urn);

        return \Closure::fromCallable([
            $this->container->make($controller),
            $action,
        ]);
    }
}
