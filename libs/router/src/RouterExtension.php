<?php

declare(strict_types=1);

namespace Railt\Router;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Railt\Contracts\Http\InputInterface;
use Railt\Foundation\Event\Resolve\FieldResolving;
use Railt\Foundation\Event\Schema\SchemaCompiling;
use Railt\Foundation\Extension\ExtensionInterface;
use Railt\Router\Instantiator\InstantiatorInterface;
use Railt\Router\Instantiator\ParamResolverAwareInstantiator;
use Railt\Router\ParamResolver\DispatcherAwareParamResolver;
use Railt\Router\ParamResolver\ParamResolverInterface;
use Railt\TypeSystem\Definition\FieldDefinition;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class RouterExtension implements ExtensionInterface
{
    /**
     * @var \WeakMap<FieldDefinition, list<Route>>
     */
    private readonly \WeakMap $routes;

    public function __construct(
        private readonly ?ContainerInterface $container = null,
        private readonly ?InstantiatorInterface $instantiator = null,
        private readonly ?ParamResolverInterface $paramResolver = null,
    ) {
        $this->routes = new \WeakMap();
    }

    public function load(EventDispatcherInterface $dispatcher): void
    {
        $dispatcher->addListener(SchemaCompiling::class, $this->onSchemaCompiling(...));
        $dispatcher->addListener(FieldResolving::class, function (FieldResolving $e) use ($dispatcher) {
            $this->onFieldResolving($dispatcher, $e);
        });
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function onFieldResolving(EventDispatcherInterface $dispatcher, FieldResolving $event): void
    {
        foreach ($this->getRoutes($dispatcher, $event->input) as $route) {
            $result = ($route->handler)($event->input);

            $event->setResult($result);

            break; // TODO add "on" matching
        }
    }

    private function onSchemaCompiling(SchemaCompiling $event): void
    {
        $event->compiler->addLoader(new RouterTypeLoader());
    }

    private function getRouteCompiler(InputInterface $input, EventDispatcherInterface $dispatcher): RouteCompiler
    {
        return new RouteCompiler(
            instantiator: $this->getInstantiator($input, $dispatcher),
            container: $this->container,
        );
    }

    private function getInstantiator(InputInterface $input, EventDispatcherInterface $dispatcher): InstantiatorInterface
    {
        return $this->instantiator ?? new ParamResolverAwareInstantiator(
            resolver: $this->getParamResolver($dispatcher),
            input: $input,
        );
    }

    private function getParamResolver(EventDispatcherInterface $dispatcher): ParamResolverInterface
    {
        return $this->paramResolver ?? new DispatcherAwareParamResolver($dispatcher);
    }

    /**
     * @return iterable<Route>
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function getRoutes(EventDispatcherInterface $dispatcher, InputInterface $input): iterable
    {
        $field = $input->getField();

        if (isset($this->routes[$field])) {
            return $this->routes[$field];
        }

        $compiler = $this->getRouteCompiler($input, $dispatcher);

        $routes = [];

        foreach ($field->getDirectives(RouterTypeLoader::DIRECTIVE_NAME) as $directive) {
            $routes[] = $compiler->compile(
                action: $directive->getValue('action'),
                on: $directive->getValue('on'),
            );
        }

        return $this->routes[$field] = $routes;
    }
}
