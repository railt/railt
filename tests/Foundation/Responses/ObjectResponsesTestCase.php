<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Foundation;

use Railt\Foundation\ConnectionInterface;
use Railt\Foundation\Event\Resolver\FieldResolve;
use Railt\Http\Request;
use Railt\Tests\Foundation\Responses\ResponsesTestCase;
use Railt\Tests\Foundation\Stub\TraversableObject;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class ObjectResponsesTestCase
 */
class ObjectResponsesTestCase extends ResponsesTestCase
{
    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function testNullableDefaultResponse(): void
    {
        $response = $this->connection()->request(new Request('{ nullable { a } }'));

        $this->assertSame(['nullable' => null], $response->getData());
        $this->assertSame([], $response->getErrors());
    }

    /**
     * @param \Closure|null $resolver
     * @return ConnectionInterface
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function connection(\Closure $resolver = null): ConnectionInterface
    {
        return parent::connection(function (EventDispatcherInterface $events) use ($resolver): void {
            if ($resolver) {
                $events->addListener(FieldResolve::class, function (FieldResolve $e) use ($resolver): void {
                    $resolver($e);
                });
            }
        });
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function testNullableResponse(): void
    {
        $response = $this->connection(function (FieldResolve $event): void {
            if ($event->getPath() === 'nullable') {
                $event->withResult(['a' => '42']);
            }
        })->request(new Request('{ nullable { a } }'));

        $this->assertSame(['nullable' => ['a' => '42']], $response->getData());
        $this->assertSame([], $response->getErrors());
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function testObjectResponseWithPublicFields(): void
    {
        $response = $this->connection(function (FieldResolve $event): void {
            if ($event->getPath() === 'nullable') {
                $event->withResult(new class() {
                    public $a = 42;
                });
            }
        })->request(new Request('{ nullable { a, b } }'));

        $this->assertSame(['nullable' => ['a' => '42', 'b' => null]], $response->getData());
        $this->assertSame([], $response->getErrors());
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function testObjectResponseIsTraversable(): void
    {
        $response = $this->connection(function (FieldResolve $event): void {
            if ($event->getPath() === 'nullable') {
                $event->withResult(new TraversableObject(['b' => 100500]));
            }
        })->request(new Request('{ nullable { a, b } }'));

        $this->assertSame(['nullable' => ['a' => null, 'b' => '100500']], $response->getData());
        $this->assertSame([], $response->getErrors());
    }

    /**
     * @return string
     */
    protected function getSchema(): string
    {
        return '
            schema {
                query: Query
            }
            
            type Query {
                nullable: Query
                non_null: Query!
                list: [Query]
                list_of_non_nulls: [Query!]
                non_null_list: [Query]!
                non_null_list_of_non_nulls: [Query!]!
                
                ## VALUES
                a: String, b: String
            }
        ';
    }
}
