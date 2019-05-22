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
use Railt\Container\Container;
use Railt\Dumper\TypeDumper;
use Railt\Foundation\Event\Http\RequestReceived;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class RailtContainerSubscriber
 */
class RailtContainerSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserData
     */
    private $context;

    /**
     * @var Container
     */
    private $app;

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
        $this->context = $clockwork->userData('railt:container')->title('Container');
    }

    /**
     * @param RequestReceived $event
     * @throws \ReflectionException
     */
    public function onRequest(RequestReceived $event): void
    {
        $this->context->table('Global Services', $this->getContainerTable($this->app));
        $this->context->table('Lifecycle Services', $this->getContainerTable($event->getConnection()));
    }

    /**
     * @param Container $container
     * @return array
     * @throws \ReflectionException
     */
    protected function getContainerTable(Container $container): array
    {
        $data = [];

        foreach ($this->extractContainer($container) as $key => $service) {
            $data[] = [
                'Name'    => $key,
                'Value'   => TypeDumper::render($service),
                'Aliases' => \implode(', ', $this->getAliases($container, $key)),
            ];
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

    /**
     * @param Container $container
     * @param string $key
     * @return array
     * @throws \ReflectionException
     */
    private function getAliases(Container $container, string $key): array
    {
        $result = [];

        foreach ($this->extractAliases($container) as $alias => $service) {
            if ($service === $key) {
                $result[] = $alias;
            }
        }

        return $result;
    }

    /**
     * @param Container $container
     * @return array
     * @throws \ReflectionException
     */
    private function extractAliases(Container $container): array
    {
        $context = (new \ReflectionObject($container))->getParentClass();

        $property = $context->getProperty('aliases');
        $property->setAccessible(true);

        return $property->getValue($container);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            RequestReceived::class => ['onRequest', 100],
        ];
    }
}
