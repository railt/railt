<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing;

use Railt\Adapters\Event;
use Railt\Container\ContainerInterface;
use Railt\Events\Dispatcher;
use Railt\Http\InputInterface;
use Railt\Reflection\Contracts\Definitions\ObjectDefinition;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Contracts\Dependent\FieldDefinition;
use Railt\Routing\Contracts\RouterInterface;
use Railt\Routing\Route\Directive;
use Railt\Runtime\Contracts\ClassLoader;

/**
 * Class FieldResolver
 */
class FieldResolver
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var Dispatcher
     */
    private $events;

    /**
     * FieldResolver constructor.
     * @param ContainerInterface $container
     * @param RouterInterface $router
     * @param Dispatcher $events
     */
    public function __construct(ContainerInterface $container, RouterInterface $router, Dispatcher $events)
    {
        $this->container = $container;
        $this->router    = $router;
        $this->events = $events;
    }

    /**
     * @param FieldDefinition $field
     * @param \Closure $inputResolver
     * @return \Closure|null
     * @throws \Railt\Routing\Exceptions\InvalidActionException
     */
    public function handle(FieldDefinition $field, \Closure $inputResolver): ?\Closure
    {
        /** @var ObjectDefinition $parent */
        $parent = $field->getParent();

        $this->loadRouteDirectives($field);

        if (! $this->router->has($field)) {
            return $this->getDefaultResult($field);
        }

        return function (...$args) use ($field, $inputResolver, $parent) {
            /** @var InputInterface $input */
            $input = $inputResolver(...$args);

            foreach ($this->router->get($field) as $route) {
                if (! $route->matchOperation($input->getOperation())) {
                    continue;
                }

                // TODO Add ability to call of multiple routes.
                return $this->call($route, $input, $field);
            }

            $default = $this->getDefaultResult($field);

            return $default instanceof \Closure ? $default() : $default;
        };
    }

    /**
     * @param FieldDefinition $field
     * @return void
     * @throws \Railt\Routing\Exceptions\InvalidActionException
     */
    private function loadRouteDirectives(FieldDefinition $field): void
    {
        $loader = $this->container->make(ClassLoader::class);

        foreach (['route', 'query', 'mutation', 'subscription'] as $route) {
            foreach ($field->getDirectives($route) as $directive) {
                $this->router->add(new Directive($this->container, $directive, $loader));
            }
        }
    }

    /**
     * @param FieldDefinition $field
     * @return \Closure|null
     */
    private function getDefaultResult(FieldDefinition $field): ?\Closure
    {
        $type = $field->getTypeDefinition();

        if ($type instanceof ObjectDefinition && ! $field->isList() && $field->isNonNull()) {
            return function (): array {
                return [];
            };
        }

        return null;
    }

    /**
     * @param Route $route
     * @param InputInterface $input
     * @param FieldDefinition $field
     * @return mixed
     */
    private function call(Route $route, InputInterface $input, FieldDefinition $field)
    {
        $parameters = $this->resolving($field, \array_merge($input->all(), [
            InputInterface::class => $input,
            TypeDefinition::class => $field,
        ]));

        return $this->resolved($field, $route->call($input, $input->getParentValue(), $parameters));
    }

    /**
     * @param FieldDefinition $field
     * @param array $parameters
     * @return mixed
     */
    private function resolving(FieldDefinition $field, array $parameters)
    {
        $event = $field->getParent()->getName() . ':' . $field->getName();

        return $this->events->dispatch(Event::RESOLVING . ':' . $event, [$field, $parameters]) ?? $parameters;
    }

    /**
     * @param FieldDefinition $field
     * @param mixed $data
     * @return mixed
     */
    private function resolved(FieldDefinition $field, $data)
    {
        $event = $field->getTypeDefinition()->getName();

        return $this->events->dispatch(Event::RESOLVED . ':' . $event, [$field, $data]) ?? $data;
    }
}
