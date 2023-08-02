<?php

declare(strict_types=1);

namespace Railt\Extension\Router;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Railt\Contracts\Http\InputInterface;
use Railt\Foundation\Event\Resolve\FieldResolving;
use Railt\Foundation\Event\Schema\SchemaCompiling;
use Railt\Foundation\Extension\ExtensionInterface;
use Railt\Extension\Router\Event\ActionDispatched;
use Railt\Extension\Router\Event\ActionDispatching;
use Railt\Extension\Router\Event\ActionFailed;
use Railt\Extension\Router\Event\ParameterResolving;
use Railt\Extension\Router\Instantiator\InstantiatorInterface;
use Railt\Extension\Router\Instantiator\ParamResolverAwareInstantiator;
use Railt\Extension\Router\ParamResolver\DispatcherAwareParamResolver;
use Railt\Extension\Router\ParamResolver\ParamResolverInterface;
use Railt\Extension\Router\ParamResolver\SimpleParamResolver;
use Railt\TypeSystem\Definition\FieldDefinition;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class RouterExtension implements ExtensionInterface
{
    /**
     * @var \WeakMap<object, list<Route>>
     */
    private readonly \WeakMap $routes;

    /**
     * @psalm-suppress PropertyNotSetInConstructor
     */
    private EventDispatcherInterface $dispatcher;

    private readonly SimpleParamResolver $simpleResolver;

    public function __construct(
        private readonly ?ContainerInterface $container = null,
        private ?InstantiatorInterface $instantiator = null,
        private ?ParamResolverInterface $paramResolver = null,
    ) {
        $this->simpleResolver = new SimpleParamResolver();
        /** @psalm-suppress MixedPropertyTypeCoercion */
        $this->routes = new \WeakMap();
    }

    public function load(EventDispatcherInterface $dispatcher): void
    {
        $this->dispatcher = $dispatcher;

        $dispatcher->addListener(SchemaCompiling::class, $this->onSchemaCompiling(...));
        $dispatcher->addListener(FieldResolving::class, $this->onFieldResolving(...));
        $dispatcher->addListener(ParameterResolving::class, $this->onParamResolving(...));
    }

    private function onSchemaCompiling(SchemaCompiling $event): void
    {
        $event->compiler->addLoader(new RouterTypeLoader());
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \Throwable
     *
     * @psalm-suppress MixedAssignment
     */
    private function onFieldResolving(FieldResolving $event): void
    {
        $resolver = $this->getParamResolver($this->dispatcher);

        foreach ($this->getRoutes($this->dispatcher, $event->input) as $route) {
            if (!$this->matchRoute($route, $event->input)) {
                continue;
            }

            $dispatching = $this->dispatcher->dispatch(new ActionDispatching(
                input: $event->input,
                route: $route,
            ));

            $arguments = [];

            foreach ($dispatching->route->parameters as $parameter) {
                foreach ($resolver->resolve($dispatching->input, $parameter) as $value) {
                    $arguments[] = $value;
                }
            }

            try {
                $result = ($route->handler)(...$arguments);

                $dispatched = $this->dispatcher->dispatch(new ActionDispatched(
                    input: $dispatching->input,
                    route: $dispatching->route,
                    result: $result,
                ));

                $event->setResult($dispatched->result);
            } catch (\Throwable $e) {
                $this->dispatcher->dispatch(new ActionFailed(
                    input: $dispatching->input,
                    route: $dispatching->route,
                    result: $e,
                ));

                throw $e;
            }
        }
    }

    /**
     * @param InputInterface<FieldDefinition> $input
     */
    private function matchRoute(Route $route, InputInterface $input): bool
    {
        if ($route->on === null) {
            return true;
        }

        /**
         * @psalm-suppress InvalidOperand : Spread operator psalm false-positive
         */
        return \in_array($route->on, [
            ...$input->getSelectedTypes(),
        ], true);
    }

    private function onParamResolving(ParameterResolving $event): void
    {
        $resolved = [...$this->simpleResolver->resolve($event->input, $event->parameter)];

        if ($resolved !== []) {
            $event->setValue(...$resolved);
            $event->stopPropagation();
        }
    }

    /**
     * @param InputInterface<FieldDefinition> $input
     */
    private function getRouteCompiler(InputInterface $input, EventDispatcherInterface $dispatcher): RouteCompiler
    {
        return new RouteCompiler(
            instantiator: $this->getInstantiator($input, $dispatcher),
            container: $this->container,
        );
    }

    /**
     * @param InputInterface<FieldDefinition> $input
     */
    private function getInstantiator(InputInterface $input, EventDispatcherInterface $dispatcher): InstantiatorInterface
    {
        return $this->instantiator ??= new ParamResolverAwareInstantiator(
            resolver: $this->getParamResolver($dispatcher),
            input: $input,
        );
    }

    private function getParamResolver(EventDispatcherInterface $dispatcher): ParamResolverInterface
    {
        return $this->paramResolver ??= new DispatcherAwareParamResolver($dispatcher);
    }

    /**
     * @param InputInterface<FieldDefinition> $input
     *
     * @return iterable<Route>
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function getRoutes(EventDispatcherInterface $dispatcher, InputInterface $input): iterable
    {
        $field = $input->getFieldDefinition();

        if (isset($this->routes[$field])) {
            return $this->routes[$field];
        }

        $compiler = $this->getRouteCompiler($input, $dispatcher);

        $routes = [];

        foreach ($field->getDirectives(RouterTypeLoader::DIRECTIVE_NAME) as $directive) {
            /**
             * @psalm-suppress MixedArgument
             */
            $routes[] = $compiler->compile(
                action: $directive->getValue('action'),
                on: $directive->getValue('on'),
            );
        }

        /**
         * @psalm-suppress InaccessibleProperty : Readonly objects can be array-accessible
         */
        return $this->routes[$field] = $routes;
    }
}
