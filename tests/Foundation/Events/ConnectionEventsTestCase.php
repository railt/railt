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
use Railt\Foundation\ConnectionInterface;
use Railt\Foundation\Event\Connection\ConnectionClosed as Closed;
use Railt\Foundation\Event\Connection\ConnectionEstablished as Established;
use Railt\Foundation\Exception\ConnectionException;
use Railt\Http\Request;
use Railt\Io\File;
use Railt\Tests\Foundation\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class ConnectionEventsTestCase
 */
class ConnectionEventsTestCase extends TestCase
{
    /**
     * @var string
     */
    private const EXAMPLE_QUERY = 'schema { query: Q } type Q { id: ID }';

    /**
     * @dataProvider eventsProvider
     *
     * @param ApplicationInterface $app
     * @param EventDispatcherInterface $dispatcher
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testConnection(ApplicationInterface $app, EventDispatcherInterface $dispatcher): void
    {
        $id = null;

        $dispatcher->addListener(Established::class, function (Established $ev) use (&$id): void {
            $this->assertNull($id);
            $this->assertSame($id = $ev->getId(), $ev->getConnection()->getId());
        });

        $dispatcher->addListener(Closed::class, function (Closed $ev) use (&$id): void {
            $this->assertNotNull($id);
            $this->assertSame($ev->getId(), $ev->getConnection()->getId());
            $this->assertSame($id, $ev->getId());
        });

        $app->connect(File::fromSources(self::EXAMPLE_QUERY));

        $this->assertNotNull($id);
    }

    /**
     * @dataProvider eventsProvider
     *
     * @param ApplicationInterface $app
     * @param EventDispatcherInterface $dispatcher
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testConnectionsClosedByGC(ApplicationInterface $app, EventDispatcherInterface $dispatcher): void
    {
        $established = $closed = 0;

        $dispatcher->addListener(Established::class, function (Established $ev) use (&$established): void {
            ++$established;
        });

        $dispatcher->addListener(Closed::class, function (Closed $ev) use (&$closed): void {
            ++$closed;
        });

        for ($i = 0; $i < 10; ++$i) {
            //
            // In this case, the GC destroys the connection object, closing it.
            //
            $app->connect(File::fromSources(self::EXAMPLE_QUERY));
        }

        $this->assertSame(10, $established);
        $this->assertSame(10, $closed);
    }

    /**
     * @dataProvider eventsProvider
     *
     * @param ApplicationInterface $app
     * @param EventDispatcherInterface $dispatcher
     * @return void
     * @throws \PHPUnit\Framework\Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testConnectionsClosedManually(ApplicationInterface $app, EventDispatcherInterface $dispatcher): void
    {
        $connections = [];
        $established = $closed = 0;

        $dispatcher->addListener(Established::class, function (Established $ev) use (&$established): void {
            ++$established;
        });

        $dispatcher->addListener(Closed::class, function (Closed $ev) use (&$closed): void {
            ++$closed;
        });

        for ($i = 0; $i < 10; ++$i) {
            $connections[] = $app->connect(File::fromSources(self::EXAMPLE_QUERY));
        }

        $this->assertSame(10, $established);
        $this->assertSame(0, $closed);
        $this->assertCount(10, $connections);

        foreach ($connections as $i => $connection) {
            $this->assertSame($i, $closed);
            $connection->close();
        }

        $this->assertSame(10, $closed);
    }

    /**
     * @dataProvider eventsProvider
     *
     * @param ApplicationInterface $app
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testUniqueIdentifiers(ApplicationInterface $app): void
    {
        /** @var ConnectionInterface[] $connections */
        $connections = [];

        for ($i = 0; $i < 10; ++$i) {
            $connections[] = $app->connect(File::fromSources(self::EXAMPLE_QUERY));
        }

        foreach ($connections as $a) {
            foreach ($connections as $b) {
                if ($a === $b) {
                    $this->assertSame($a->getId(), $b->getId());
                } else {
                    $this->assertNotSame($a->getId(), $b->getId());
                }
            }
        }
    }

    /**
     * @dataProvider eventsProvider
     *
     * @param ApplicationInterface $app
     * @param EventDispatcherInterface $dispatcher
     * @return void
     * @throws \PHPUnit\Framework\Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testPropagationWasStopped(ApplicationInterface $app, EventDispatcherInterface $dispatcher): void
    {
        $this->expectException(ConnectionException::class);
        $this->expectExceptionMessage('Connection was closed and can no longer process requests');

        //
        // Block the event.
        //
        $dispatcher->addListener(Established::class, function (Established $event): void {
            $event->stopPropagation();
        });

        $established = null;

        //
        // This event never call because the event was blocked in the previous step.
        //
        $dispatcher->addListener(Established::class, function (Established $event) use (&$established): void {
            $established = true;
        });

        //
        // 1) During the closure of the connection, check that the second event was not triggered.
        // 2) Update an "$established" variable in order to check that the closing event was generally caused.
        //
        $dispatcher->addListener(Closed::class, function () use (&$established): void {
            $this->assertNull($established);

            $established = false;
        });

        $app->connect(File::fromSources(self::EXAMPLE_QUERY))
            ->request(new Request('{}'));

        $this->assertFalse($established);
    }
}
