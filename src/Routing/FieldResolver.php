<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing;

use Railt\Container\ContainerInterface;
use Railt\Http\InputInterface;
use Railt\Reflection\Contracts\Definitions\ObjectDefinition;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Contracts\Dependent\FieldDefinition;
use Railt\Routing\Contracts\RouterInterface;

/**
 * Class FieldResolver
 */
class FieldResolver
{
    private const DIRECTIVE = 'route';

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
     * @param RouterInterface $router
     */
    public function __construct(ContainerInterface $container, RouterInterface $router)
    {
        $this->container = $container;
        $this->router    = $router;
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

        $this->loadRouteDirectives($parent, $field);

        if (! $this->router->has($parent)) {
            return $this->getDefaultResult($field);
        }

        return function (...$args) use ($field, $inputResolver, $parent) {
            /** @var InputInterface $input */
            $input = $inputResolver(...$args);

            foreach ($this->router->get($parent) as $route) {
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
     * @param Route $route
     * @param InputInterface $input
     * @param FieldDefinition $field
     * @return mixed
     */
    private function call(Route $route, InputInterface $input, FieldDefinition $field)
    {
        // TODO Add ability to customize action arguments

        $parameters = \array_merge($input->all(), [
            InputInterface::class => $input,
            TypeDefinition::class => $field,
        ]);

        return $route->call($input, $input->getParentValue(), $parameters);
    }

    /**
     * @param TypeDefinition $object
     * @param FieldDefinition $field
     * @return void
     * @throws \Railt\Routing\Exceptions\InvalidActionException
     */
    private function loadRouteDirectives(TypeDefinition $object, FieldDefinition $field): void
    {
        foreach ($field->getDirectives(self::DIRECTIVE) as $directive) {
            $this->router->add(new DirectiveRoute($this->container, $object, $directive));
        }
    }

    /**
     * @param FieldDefinition $field
     * @return \Closure|null
     */
    private function getDefaultResult(FieldDefinition $field): ?\Closure
    {
        $type = $field->getTypeDefinition();

        if ($type instanceof ObjectDefinition && ! $field->isList() && ! $field->isNonNull()) {
            return function (): array {
                return [];
            };
        }

        return null;
    }
}
