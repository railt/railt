<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Debug\Clockwork;

use Clockwork\Clockwork;
use Clockwork\Request\UserData;
use Illuminate\Support\Arr;
use Railt\Container\Container;
use Railt\Dumper\TypeDumper;
use Railt\Foundation\Event\Connection\ConnectionEstablished;
use Railt\Foundation\Event\Http\RequestReceived;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class GraphQLRequestSubscriber
 */
class GraphQLUserDataSubscriber implements EventSubscriberInterface
{
    /**
     * @var Container
     */
    private $app;

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
        $this->data = $clockwork->userData('railt')->title('Railt');

        $this->shareContainer();
    }

    /**
     * @throws \ReflectionException
     */
    private function shareContainer(): void
    {
        $this->data->table('Container', $this->loadTableFromContainer($this->app));
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ConnectionEstablished::class => ['onConnect', -100],
            RequestReceived::class => ['onRequest', -100],
        ];
    }

    /**
     * @param Container $container
     * @throws \ReflectionException
     */
    private function shareContext(Container $container): void
    {
        $this->data->table('Context', $this->loadTableFromContainer($container));
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
     * @param RequestReceived $event
     */
    public function onRequest(RequestReceived $event): void
    {
        $request = $event->getRequest();

        $this->data->counters([
            'Variables' => \count($request->getVariables(), \COUNT_RECURSIVE)
        ]);

        $this->data->table('Request', [
            ['Description' => 'Connection', 'Value' => $event->getConnection()->getId()],
            ['Description' => 'ID', 'Value' => $request->getId()],
            ['Description' => 'Type', 'Value' => $request->getQueryType()],
            ['Description' => 'Query', 'Value' => $request->getQuery()],
            ['Description' => 'Operation', 'Value' => $request->getOperation()],
        ]);

        $variables = [];

        foreach (Arr::dot($request->getVariables()) as $key => $value) {
            $variables[] = ['Name' => $key, 'Value' => TypeDumper::render($value)];
        }

        $this->data->table('Variables', $variables);
    }

    /**
     * @param Container $container
     * @return array
     * @throws \ReflectionException
     */
    private function loadTableFromContainer(Container $container): array
    {
        $data = [];

        foreach ($this->extractContainer($container) as $key => $service) {
            $data[] = ['Service' => $key, 'Value' => TypeDumper::render($service)];
        }

        return $data;
    }

    /**
     * @param Container $container
     * @return array
     * @throws \ReflectionException
     */
    private function extractContainer(Container $container): array
    {
        $context = (new \ReflectionObject($container))->getParentClass();

        $property = $context->getProperty('registered');
        $property->setAccessible(true);

        return $property->getValue($container);
    }
}
