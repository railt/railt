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
use Illuminate\Support\Arr;
use Railt\Container\Exception\ContainerInvocationException;
use Railt\Container\Exception\ContainerResolutionException;
use Railt\Container\Exception\ParameterResolutionException;
use Railt\Dumper\TypeDumper;
use Railt\Container\Container;
use Clockwork\Request\UserData;
use Railt\Http\RequestInterface;
use Railt\Extension\Routing\RouterInterface;
use Railt\Foundation\Event\Http\RequestReceived;
use Railt\Foundation\Event\Http\ResponseProceed;
use Railt\Foundation\Event\Resolver\FieldResolve;
use Railt\Foundation\Event\Connection\ConnectionEstablished;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class RailtRequestSubscriber
 */
class RailtRequestSubscriber implements EventSubscriberInterface
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
     * @var UserData
     */
    private $data;

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
            RequestReceived::class => ['onRequest', 100],
            ResponseProceed::class => ['onResponse', -100],
            FieldResolve::class    => ['onResolve', -1],
        ];
    }

    /**
     * @param FieldResolve $event
     */
    public function onResolve(FieldResolve $event): void
    {
        $request = $event->getRequest();
        $input = $event->getInput();

        /** @var UserData $context */
        $context = $this->clockwork->userData('railt:request:' . $request->getId());

        if ($event->hasResult()) {
            $context->table('GraphQL Field [' . $input->getPath() . ']', [
                ['Name' => 'Path', 'Value' => $input->getPath()],
                ['Name' => 'Type', 'Value' => $input->getTypeName()],
                ['Name' => 'Field', 'Value' => $input->getField()],
                ['Name' => 'Alias', 'Value' => $input->getAlias()],
                ['Name' => 'Arguments', 'Value' => $input->all()],
                ['Name' => 'Prefer Types', 'Value' => $input->getPreferTypes()],
                ['Name' => 'Result', 'Value' => TypeDumper::render($event->getResult())],
                ['Name' => 'Related Fields', 'Value' => $input->getRelatedFields()],
            ]);
        }
    }

    /**
     * @param RequestReceived $event
     * @throws \ReflectionException
     */
    public function onRequest(RequestReceived $event): void
    {
        $request = $event->getRequest();

        /** @var UserData $context */
        $context = $this->clockwork->userData('railt:request:' . $request->getId())->title('GraphQL Request');

        $context->counters([
            'Connection ID' => $event->getConnection()->getId(),
            'Request ID'    => $request->getId(),
            'Variables'     => \count($request->getVariables(), \COUNT_RECURSIVE),
        ]);

        $context->table('Request', [
            ['Description' => 'ID', 'Value' => $request->getId()],
            ['Description' => 'Type', 'Value' => $request->getQueryType()],
            ['Description' => 'Query', 'Value' => $request->getQuery()],
            ['Description' => 'Operation', 'Value' => $request->getOperation()],
        ]);

        $this->shareVariables($request, $context);
    }

    /**
     * @param RequestInterface $request
     * @param UserData $context
     */
    private function shareVariables(RequestInterface $request, UserData $context): void
    {
        $variables = [];

        foreach (Arr::dot($request->getVariables()) as $key => $value) {
            $value = \is_scalar($value) ? $value : TypeDumper::render($value);

            $variables[] = ['Name' => $key, 'Value' => $value];
        }

        if (\count($variables)) {
            $context->table('Variables', $variables);
        }
    }

    /**
     * @param ResponseProceed $event
     * @throws ContainerInvocationException
     * @throws ContainerResolutionException
     * @throws ParameterResolutionException
     */
    public function onResponse(ResponseProceed $event): void
    {
        $context = $this->clockwork->userData('railt:request:' . $event->getRequest()->getId());

        $this->shareRouter($context);
    }

    /**
     * @throws ContainerInvocationException
     * @throws ContainerResolutionException
     * @throws ParameterResolutionException
     */
    private function shareRouter(UserData $context): void
    {
        if ($this->app->has(RouterInterface::class)) {
            $router = $this->app->make(RouterInterface::class);

            $routes = [];

            foreach ($router->all() as $route) {
                $filter = [];

                foreach ($route->filters() as $name => $values) {
                    $filter[] = $name . ': ' . \implode(', ', $values);
                }

                $routes[] = [
                    'Action'  => TypeDumper::render($route->getAction()),
                    'Filters' => \implode('; ', $filter),
                ];
            }

            if (\count($routes)) {
                $context->table('Routes', $routes);
            }
        }
    }

    /**
     * @param ConnectionEstablished $event
     * @throws \ReflectionException
     */
    public function onConnect(ConnectionEstablished $event): void
    {
        /** @var Container $connection */
        $connection = $event->getConnection();

        $this->shareContext($connection);
    }

    /**
     * @param Container $container
     * @throws \ReflectionException
     */
    private function shareContext(Container $container): void
    {
        $this->data->table('Context', $this->loadTableFromContainer($container));
    }
}
