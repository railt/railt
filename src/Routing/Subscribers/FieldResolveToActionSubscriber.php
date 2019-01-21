<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing\Subscribers;

use Railt\Foundation\Event\Resolver\FieldResolve;
use Railt\Http\Identifiable;
use Railt\Http\InputInterface;
use Railt\Http\RequestInterface;
use Railt\Routing\Events\ActionDispatch;
use Railt\Routing\RouteInterface;
use Railt\Routing\RouterInterface;
use Railt\SDL\Contracts\Definitions\TypeDefinition;
use Railt\SDL\Contracts\Dependent\FieldDefinition;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ResolverSubscriber
 */
class FieldResolveToActionSubscriber implements EventSubscriberInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var EventDispatcherInterface
     */
    private $events;

    /**
     * ResolverSubscriber constructor.
     *
     * @param RouterInterface $router
     * @param EventDispatcherInterface $events
     */
    public function __construct(RouterInterface $router, EventDispatcherInterface $events)
    {
        $this->router = $router;
        $this->events = $events;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FieldResolve::class => ['onFieldResolve'],
        ];
    }

    /**
     * @param FieldResolve $event
     */
    public function onFieldResolve(FieldResolve $event): void
    {
        $routes = $this->router->resolve($event->getRequest(), $event->getInput());

        if ($this->isListType($event)) {
            $result = $this->list($routes, $event);

            if (\count($result) > 0) {
                $event->withResult($result);

                return;
            }

            return;
        }

        if (($result = $this->singular($routes, $event)) !== null) {
            $event->withResult($result);
        }
    }

    /**
     * @param FieldResolve $event
     * @return bool
     */
    private function isListType(FieldResolve $event): bool
    {
        return $event->getFieldDefinition()->isList();
    }

    /**
     * @param iterable|RouteInterface[] $routes
     * @param \Railt\Foundation\Event\Resolver\FieldResolve $event
     * @return array
     */
    private function list(iterable $routes, FieldResolve $event): array
    {
        $result = [];

        foreach ($routes as $route) {
            $before = $this->fireDispatch($route, $event);

            if ($before->isPropagationStopped()) {
                continue;
            }

            foreach ($before->getResponse() as $response) {
                $result[] = $this->result($route, $response);
            }
        }

        return $result;
    }

    /**
     * @param RouteInterface $route
     * @param FieldResolve $resolving
     * @return ActionDispatch|\Symfony\Component\EventDispatcher\Event
     */
    private function fireDispatch(RouteInterface $route, FieldResolve $resolving): ActionDispatch
    {
        $arguments = $this->getMethodArguments($route, $resolving);

        $event = new ActionDispatch($route->getAction(), $arguments);

        return $this->events->dispatch(ActionDispatch::class, $event);
    }

    /**
     * @param RouteInterface $route
     * @param FieldResolve $event
     * @return array
     */
    private function getMethodArguments(RouteInterface $route, FieldResolve $event): array
    {
        $arguments = [
            //
            // Inject current route
            //
            RouteInterface::class   => $route,

            //
            // Inject request environment
            //
            InputInterface::class   => $event->getInput(),
            RequestInterface::class => $event->getRequest(),
            Identifiable::class     => $event->getConnection(),

            //
            // Inject reflection environment
            //
            FieldDefinition::class  => $event->getFieldDefinition(),
            TypeDefinition::class   => $event->getTypeDefinition(),
        ];

        //
        // Inject query arguments
        //
        foreach ($event->getInput()->all() as $argument => $value) {
            $arguments['$' . $argument] = $value;
        }

        //
        // Inject parent response
        //
        $arguments['$parent'] = $event->getParentResult();

        return $arguments;
    }

    /**
     * @param \Railt\Routing\RouteInterface $route
     * @param mixed $result
     * @return mixed
     */
    private function result(RouteInterface $route, $result)
    {
        if (\is_array($result) && ! isset($result['__typename'])) {
            return \array_merge($result, ['__typename' => $route->getPreferType()]);
        }

        return $result;
    }

    /**
     * @param iterable|RouteInterface[] $routes
     * @param \Railt\Foundation\Event\Resolver\FieldResolve $event
     * @return mixed
     */
    private function singular(iterable $routes, FieldResolve $event)
    {
        foreach ($routes as $route) {
            $before = $this->fireDispatch($route, $event);

            if ($before->isPropagationStopped()) {
                continue;
            }

            if ($before->hasResponse()) {
                return $this->result($route, $before->getResponse());
            }
        }

        return null;
    }
}
