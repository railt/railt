<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Extension\Debug\Clockwork;

use Clockwork\Clockwork;
use Railt\Dumper\TypeDumper;
use Railt\Container\Container;
use Clockwork\Request\UserData;
use Railt\Extension\Routing\RouterInterface;
use Railt\Foundation\Event\Http\ResponseProceed;
use Railt\Container\Exception\ContainerInvocationException;
use Railt\Container\Exception\ContainerResolutionException;
use Railt\Container\Exception\ParameterResolutionException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class RailtRoutingSubscriber
 */
class RailtRoutingSubscriber implements EventSubscriberInterface
{
    /**
     * @var Container
     */
    private $app;

    /**
     * @var Clockwork
     */
    private $clockwork;

    /**
     * FieldResolveSubscriber constructor.
     *
     * @param Clockwork $clockwork
     * @param Container $app
     */
    public function __construct(Clockwork $clockwork, Container $app)
    {
        $this->app = $app;
        $this->clockwork = $clockwork;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ResponseProceed::class => ['onResponse', -100],
        ];
    }

    /**
     * @param ResponseProceed $event
     * @throws ContainerInvocationException
     * @throws ContainerResolutionException
     * @throws ParameterResolutionException
     */
    public function onResponse(ResponseProceed $event): void
    {
        /** @var UserData $context */
        $context = $this->clockwork
            ->userData('railt-routing-' . $event->getRequest()->getId())
            ->title('Routing');

        $this->shareRouter($context);
    }

    /**
     * @throws ContainerInvocationException
     * @throws ContainerResolutionException
     * @throws ParameterResolutionException
     */
    private function shareRouter(UserData $context): void
    {
        if (! $this->app->has(RouterInterface::class)) {
            return;
        }

        $router = $this->app->make(RouterInterface::class);

        $routes = [];

        /** @var \Railt\Extension\Routing\RouteInterface $route */
        foreach ($router->all() as $route) {
            $filter = [];

            foreach ($route->filters() as $name => $values) {
                $filter[] = $name . ': ' . \implode(', ', $values);
            }

            $routes[] = [
                'Action' => \is_callable($route->getAction())
                    ? TypeDumper::render($route->getAction())
                    : $route->getAction(),

                'Filters' => \implode('; ', $filter),

                'Type' => $route->getPreferType()
                    ? $route->getPreferType()
                    : 'Any',
            ];
        }

        if (\count($routes)) {
            $context->table('Handled Routes', $routes);
        }
    }
}
