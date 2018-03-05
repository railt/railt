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
        $this->events    = $events;
    }

    /**
     * @param FieldDefinition $field
     * @param \Closure $inputResolver
     * @return \Closure|null
     */
    public function handle(FieldDefinition $field, \Closure $inputResolver): ?\Closure
    {
        /** @var ObjectDefinition $parent */
        $parent = $field->getParent();

        $this->loadRouteDirectives($field);

        return function (...$args) use ($field, $inputResolver, $parent) {
            $parent = $args[0]; // TODO

            /** @var InputInterface $input */
            $input = $inputResolver(...$args);

            foreach ($this->router->get($field) as $route) {
                if (! $route->matchOperation($input->getOperation())) {
                    continue;
                }

                // TODO Add ability to call of multiple routes.
                return $this->call($route, $input, $field);
            }

            if (\is_object($parent) && !($parent instanceof \ArrayAccess)) {
                throw new \InvalidArgumentException('Bad parent type (object), but array or scalar required');
            }

            return $this->resolved($field, $parent[$field->getName()] ?? null);
        };
    }

    /**
     * @param FieldDefinition $field
     * @return void
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
