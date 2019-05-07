<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Routing\Events;

use Illuminate\Support\Arr;
use Railt\Component\Dumper\TypeDumper;
use Railt\Component\Http\Request;
use Railt\Component\Io\File;
use Railt\Extension\Routing\Events\ActionDispatch;
use Railt\Extension\Routing\RouterInterface;
use Railt\Foundation\ApplicationInterface;
use Railt\Foundation\ConnectionInterface;
use Railt\Tests\Foundation\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class ActionEventsTestCase
 */
class ActionEventsTestCase extends TestCase
{
    /**
     * @var string
     */
    private const EXAMPLE_QUERY = 'schema { query: Q } type Q { id: ID }';

    /**
     * @return array
     * @throws \Railt\Component\Container\Exception\ContainerInvocationException
     * @throws \Railt\Component\Container\Exception\ContainerResolutionException
     * @throws \Railt\Component\Container\Exception\ParameterResolutionException
     */
    public function provider(): array
    {
        $app = $this->app();

        return [
            [
                $app->connect(File::fromSources(self::EXAMPLE_QUERY)),
                $app->make(EventDispatcherInterface::class),
                $app->make(RouterInterface::class),
            ],
        ];
    }

    /**
     * @dataProvider provider
     *
     * @param ConnectionInterface $c
     * @param EventDispatcherInterface $e
     * @param RouterInterface $r
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testActionEventWithoutRoute(
        ConnectionInterface $c,
        EventDispatcherInterface $e,
        RouterInterface $r
    ): void {
        $dispatched = false;

        $e->addListener(ActionDispatch::class, function (ActionDispatch $event) use (&$dispatched): void {
            $dispatched = true;
        });

        $c->request(new Request('{ id }'));

        $this->assertFalse($dispatched, 'ActionDispatch was not fired');
    }

    /**
     * @dataProvider provider
     *
     * @param ConnectionInterface $c
     * @param EventDispatcherInterface $e
     * @param RouterInterface $r
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testActionEventWithRoute(
        ConnectionInterface $c,
        EventDispatcherInterface $e,
        RouterInterface $r
    ): void {
        // Add route
        $r->create(function () {
            return 23;
        })->whereField('id');

        $dispatched = false;

        $e->addListener(ActionDispatch::class, function (ActionDispatch $event) use (&$dispatched): void {
            $dispatched = true;
        });

        $c->request(new Request('{ id }'));

        $this->assertTrue($dispatched, 'ActionDispatch was fired');
    }

    /**
     * @dataProvider provider
     *
     * @param ConnectionInterface $c
     * @param EventDispatcherInterface $e
     * @param RouterInterface $r
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testActionSignatureAutowiring(
        ConnectionInterface $c,
        EventDispatcherInterface $e,
        RouterInterface $r
    ): void {
        // Add route
        $r->create(function (\stdClass $object) {
            return \get_class($object);
        })->whereField('id');


        $e->addListener(ActionDispatch::class, function (ActionDispatch $event): void {
            $event->withArgument(\stdClass::class, new \stdClass());
        });

        $response = $c->request(new Request('{ id }'));

        $this->assertSame(\stdClass::class, Arr::get($response->toArray(), 'data.id'));
    }

    /**
     * @dataProvider provider
     *
     * @param ConnectionInterface $c
     * @param EventDispatcherInterface $e
     * @param RouterInterface $r
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testActionVariableNameAutowiring(
        ConnectionInterface $c,
        EventDispatcherInterface $e,
        RouterInterface $r
    ): void {
        // Add route
        $r->create(function ($variable) {
            return $variable;
        })->whereField('id');


        $e->addListener(ActionDispatch::class, function (ActionDispatch $event): void {
            $event->withArgument('$variable', 42);
        });

        $response = $c->request(new Request('{ id }'));

        $this->assertSame('42', Arr::get($response->toArray(), 'data.id'));
    }

    /**
     * @dataProvider provider
     *
     * @param ConnectionInterface $c
     * @param EventDispatcherInterface $e
     * @param RouterInterface $r
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception
     */
    public function testActionMultipleArgumentsAutowiring(
        ConnectionInterface $c,
        EventDispatcherInterface $e,
        RouterInterface $r
    ): void {
        // Add route
        $r->create(function ($a, \stdClass $b, ApplicationInterface $app) {
            return
                TypeDumper::render($a) . ' + ' .
                TypeDumper::render($b) . ' + ' .
                TypeDumper::render($app);
        })->whereField('id');


        $e->addListener(ActionDispatch::class, function (ActionDispatch $event): void {
            $event->withArguments(['$a' => 42, \stdClass::class => new \stdClass()]);
        });

        $response = $c->request(new Request('{ id }'));

        $this->assertIsString(Arr::get($response->toArray(), 'data.id'));
    }
}
