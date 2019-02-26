<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Foundation\Events;

use Railt\Foundation\ApplicationInterface;
use Railt\Foundation\Event\Http\RequestReceived;
use Railt\Foundation\Event\Http\ResponseProceed;
use Railt\Http\Request;
use Railt\Io\File;
use Railt\Tests\Foundation\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class RequestEventsTestCase
 */
class RequestEventsTestCase extends TestCase
{
    /**
     * @var string
     */
    private const EXAMPLE_QUERY = 'schema { query: Q, mutation: Q, subscription: Q } type Q { id: ID }';

    /**
     * @dataProvider eventsProvider
     *
     * @param ApplicationInterface $app
     * @param EventDispatcherInterface $ev
     */
    public function testRequest(ApplicationInterface $app, EventDispatcherInterface $ev): void
    {
        $response = $request = $connection = null;

        $ev->addListener(RequestReceived::class,
            function (RequestReceived $event) use (&$connection, &$requests, &$request): void {
                ++$requests;
                $connection = $event->getConnection();
                $request = $event->getRequest();
            });

        $ev->addListener(ResponseProceed::class,
            function (ResponseProceed $event) use (&$connection, &$responses, &$response): void {
                ++$responses;
                $connection = $event->getConnection();
                $response = $event->getResponse();
            });

        for ($i = 0; $i < 10; ++$i) {
            $needleConnection = $app->connect(File::fromSources(self::EXAMPLE_QUERY));
            $requests = $responses = 0;

            for ($j = 0; $j < 10; ++$j) {
                $needleResponse = $needleConnection->request(
                    $needleRequest = new Request('{ id }')
                );

                $this->assertSame($request, $needleRequest);
                $this->assertSame($response, $needleResponse);
            }

            $this->assertSame($connection, $needleConnection);
            $this->assertSame(10, $requests);
            $this->assertSame(10, $responses);
        }
    }

    /**
     * @dataProvider eventsProvider
     * @param ApplicationInterface $app
     * @param EventDispatcherInterface $dispatcher
     */
    public function testQueryRequestType(ApplicationInterface $app, EventDispatcherInterface $dispatcher): void
    {
        $dispatcher->addListener(ResponseProceed::class, function (ResponseProceed $ev): void {
            $request = $ev->getRequest();

            $this->assertSame(Request::TYPE_QUERY, $request->getQueryType());
            $this->assertTrue($request->isQuery());
        });

        $app->connect(File::fromSources(self::EXAMPLE_QUERY))
            ->request(new Request('{ id }'));
    }

    /**
     * @dataProvider eventsProvider
     * @param ApplicationInterface $app
     * @param EventDispatcherInterface $dispatcher
     */
    public function testMutationRequestType(ApplicationInterface $app, EventDispatcherInterface $dispatcher): void
    {
        $dispatcher->addListener(ResponseProceed::class, function (ResponseProceed $ev): void {
            $request = $ev->getRequest();

            $this->assertSame(Request::TYPE_MUTATION, $request->getQueryType());
            $this->assertTrue($request->isMutation());
        });

        $app->connect(File::fromSources(self::EXAMPLE_QUERY))
            ->request(new Request('mutation { id }'));
    }

    /**
     * @dataProvider eventsProvider
     * @param ApplicationInterface $app
     * @param EventDispatcherInterface $dispatcher
     */
    public function testSubscriptionRequestType(ApplicationInterface $app, EventDispatcherInterface $dispatcher): void
    {
        $dispatcher->addListener(ResponseProceed::class, function (ResponseProceed $ev): void {
            $request = $ev->getRequest();

            $this->assertSame(Request::TYPE_SUBSCRIPTION, $request->getQueryType());
            $this->assertTrue($request->isSubscription());
        });

        $app->connect(File::fromSources(self::EXAMPLE_QUERY))
            ->request(new Request('subscription { id }'));
    }
}
