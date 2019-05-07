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
use Clockwork\Request\UserData;
use Illuminate\Support\Arr;
use Railt\Component\Container\Container;
use Railt\Component\Dumper\TypeDumper;
use Railt\Component\Http\RequestInterface;
use Railt\Extension\Routing\RouterInterface;
use Railt\Foundation\Event\Connection\ConnectionEstablished;
use Railt\Foundation\Event\Http\RequestReceived;
use Railt\Foundation\Event\Http\ResponseProceed;
use Railt\Foundation\Event\Resolver\FieldResolve;

/**
 * Class RequestUserDataSubscriber
 */
class HttpLifecycleUserDataSubscriber extends UserDataSubscriber
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
     * @throws \ReflectionException
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

        $context->table('Dispatch [' . $input->getPath() . ']', [
            ['Name' => 'GraphQL Type', 'Value' => $input->getTypeName()],
            ['Name' => 'GraphQL Field', 'Value' => $input->getField()],
            ['Name' => 'Alias', 'Value' => $input->getAlias()],
            ['Name' => 'Path', 'Value' => $input->getPath()],
            ['Name' => 'Arguments', 'Value' => $input->all()],
            ['Name' => 'Prefer Types', 'Value' => $input->getPreferTypes()],
            ['Name' => 'Result', 'Value' => TypeDumper::render($event->getResult())],
            ['Name' => 'Related Fields', 'Value' => $input->getRelatedFields()],
        ]);
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

        $context->table('Context Container', $this->getContainerTable($event->getConnection()));

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

        $context->table('Variables', $variables);
    }

    /**
     * @param ResponseProceed $event
     * @throws \Railt\Component\Container\Exception\ContainerInvocationException
     * @throws \Railt\Component\Container\Exception\ContainerResolutionException
     * @throws \Railt\Component\Container\Exception\ParameterResolutionException
     */
    public function onResponse(ResponseProceed $event): void
    {
        $context = $this->clockwork->userData('railt:request:' . $event->getRequest()->getId());

        $this->shareRouter($context);
    }

    /**
     * @throws \Railt\Component\Container\Exception\ContainerInvocationException
     * @throws \Railt\Component\Container\Exception\ContainerResolutionException
     * @throws \Railt\Component\Container\Exception\ParameterResolutionException
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
