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
use Railt\Http\RequestInterface;
use Railt\Reflection\Contracts\Definitions\ObjectDefinition;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Contracts\Dependent\FieldDefinition;
use Railt\Reflection\Contracts\Invocations\DirectiveInvocation;
use Railt\Routing\Contracts\RouterInterface;

/**
 * Class FieldResolver
 */
class FieldResolver
{
    private const DIRECTIVE           = 'route';
    private const DIRECTIVE_ACTION    = 'action';
    private const DIRECTIVE_OPERATION = 'operation';
    private const DIRECTIVE_RELATION  = 'relation';

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
     * @param RequestInterface $request
     * @param \Closure $inputResolver
     * @return \Closure|null
     */
    public function handle(FieldDefinition $field, RequestInterface $request, \Closure $inputResolver): ?\Closure
    {
        /** @var ObjectDefinition $parent */
        $parent = $field->getParent();

        $this->loadRouteDirective($parent, $field);

        if (! $this->router->has($parent)) {
            return $this->getDefaultResult($field);
        }

        $route = $this->router->get($parent);

        // TODO Match operation
        // $request->getOperation()

        // TODO Match method
        // $request->getMethod()

        return function (...$args) use ($route, $field, $inputResolver) {
            /** @var InputInterface $input */
            $input = $inputResolver(...$args);

            return $route->call([
                InputInterface::class => $input,
                TypeDefinition::class => $field,
                'parent'              => $input->getParentValue(),
            ]);
        };
    }

    /**
     * @param TypeDefinition $object
     * @param FieldDefinition $field
     * @return void
     */
    private function loadRouteDirective(TypeDefinition $object, FieldDefinition $field): void
    {
        if (! $field->hasDirective(self::DIRECTIVE)) {
            return;
        }

        /** @var DirectiveInvocation $directive */
        $directive = $field->getDirective(self::DIRECTIVE);

        $urn      = (string)$directive->getPassedArgument(self::DIRECTIVE_ACTION);
        $relation = $directive->getPassedArgument(self::DIRECTIVE_RELATION);

        $route = $this->router->route($object, $field->getName())
            ->then($this->createCallback($urn));

        if ($relation['parent'] && $relation['child']) {
            $route->relation($relation['parent'], $relation['child']);
        }

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
