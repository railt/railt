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
use Railt\Foundation\Kernel\Contracts\ClassLoader;
use Railt\Http\InputInterface;
use Railt\Reflection\Contracts\Definitions\ObjectDefinition;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Contracts\Dependent\FieldDefinition;
use Railt\Routing\Contracts\RouterInterface;
use Railt\Routing\Route\Directive;

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
     * @param $parent
     * @param FieldDefinition $field
     * @param InputInterface $input
     * @return array|mixed
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function handle($parent, FieldDefinition $field, InputInterface $input)
    {
        $this->loadRouteDirectives($field);

        foreach ($this->router->get($field) as $route) {
            if (! $route->matchOperation($input->getOperation())) {
                continue;
            }

            // TODO Add ability to call of multiple routes.
            return $this->resolved($field, $this->call($route, $input, $field));
        }

        if ($parent === null && $field->getTypeDefinition() instanceof ObjectDefinition && $field->isNonNull()) {
            return [];
        }

        return $this->resolved($field, $parent[$field->getName()] ?? null);
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
     * @param FieldDefinition $field
     * @param mixed $data
     * @return mixed
     * @throws \RuntimeException
     */
    private function resolved(FieldDefinition $field, $data)
    {
        $this->verifyResult($field, $data);

        $event = $field->getTypeDefinition()->getName();

        return $this->events->dispatch(Event::RESOLVED . ':' . $event, [$field, $data]) ?? $data;
    }

    /**
     * @param FieldDefinition $field
     * @param mixed $data
     * @throws \RuntimeException
     */
    private function verifyResult(FieldDefinition $field, $data): void
    {
        $valid = $field->isList() ? (\is_array($data) || \is_iterable($data)) : \is_scalar($data);

        if (! $valid) {
            $type = \mb_strtolower(\gettype($data));
            $args = [$field, $field->isList() ? 'iterable' : 'scalar', $type];

            throw new \RuntimeException(\vsprintf('Response type of %s must be %s, but %s given', $args));
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

        return $route->call($input, $input->getParentValue(), $parameters);
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
}
