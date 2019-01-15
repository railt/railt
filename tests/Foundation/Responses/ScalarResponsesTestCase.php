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
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class ScalarResponsesTestCase
 */
class ScalarResponsesTestCase extends ResponsesTestCase
{
    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function testNullableDefaultResponse(): void
    {
        $response = $this->connection()->request(new Request('{ nullable }'));

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
            $event->withResult('42');
        })->request(new Request('{ nullable }'));

        $this->assertSame(['nullable' => '42'], $response->getData());
        $this->assertSame([], $response->getErrors());
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\Exception
     */
    public function testNonNullDefaultResponse(): void
    {
        $response = $this->connection()->request(new Request('{ non_null }'));

        $this->assertNull($response->getData());
        $this->assertCount(1, $response->getErrors());
        $this->assertSame('Cannot return null for non-nullable field Query.non_null.',
            \array_get($response->getErrors(), '0.message'));
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function testNonNullResponse(): void
    {
        $response = $this->connection(function (FieldResolve $event): void {
            $event->withResult('42');
        })->request(new Request('{ non_null }'));

        $this->assertSame(['non_null' => '42'], $response->getData());
        $this->assertSame([], $response->getErrors());
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\Exception
     */
    public function testNonNullInvalidResponse(): void
    {
        $response = $this->connection(function (FieldResolve $event): void {
            $event->withResult(['42']);
        })->request(new Request('{ non_null }'));

        $this->assertNull($response->getData());
        $this->assertCount(1, $response->getErrors());
        $this->assertSame('Expected a value of type "String" but received: ["42"]',
            \array_get($response->getErrors(), '0.message'));
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function testListDefaultResponse(): void
    {
        $response = $this->connection()->request(new Request('{ list }'));

        $this->assertSame(['list' => null], $response->getData());
        $this->assertSame([], $response->getErrors());
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function testListResponse(): void
    {
        $response = $this->connection(function (FieldResolve $event): void {
            $event->withResult(['42']);
        })->request(new Request('{ list }'));

        $this->assertSame(['list' => ['42']], $response->getData());
        $this->assertSame([], $response->getErrors());
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function testListResponseWithNulls(): void
    {
        $response = $this->connection(function (FieldResolve $event): void {
            $event->withResult([null, 42, null]);
        })->request(new Request('{ list }'));

        $this->assertSame(['list' => [null, '42', null]], $response->getData());
        $this->assertSame([], $response->getErrors());
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\Exception
     */
    public function testListInvalidResponse(): void
    {
        $response = $this->connection(function (FieldResolve $event): void {
            $event->withResult('42');
        })->request(new Request('{ list }'));

        $this->assertSame(['list' => null], $response->getData());
        $this->assertCount(1, $response->getErrors());
        $this->assertSame('User Error: expected iterable, but did not find one for field Query.list.',
            \array_get($response->getErrors(), '0.message'));
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function testListOfNonNullsDefaultResponse(): void
    {
        $response = $this->connection()->request(new Request('{ list_of_non_nulls }'));

        $this->assertSame(['list_of_non_nulls' => null], $response->getData());
        $this->assertSame([], $response->getErrors());
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function testListOfNonNullsResponse(): void
    {
        $response = $this->connection(function (FieldResolve $event): void {
            $event->withResult(['42']);
        })->request(new Request('{ list_of_non_nulls }'));

        $this->assertSame(['list_of_non_nulls' => ['42']], $response->getData());
        $this->assertSame([], $response->getErrors());
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\Exception
     */
    public function testListOfNonNullsResponseWithNulls(): void
    {
        $response = $this->connection(function (FieldResolve $event): void {
            $event->withResult([null, 42, null]);
        })->request(new Request('{ list_of_non_nulls }'));

        $this->assertSame(['list_of_non_nulls' => null], $response->getData());
        $this->assertCount(1, $response->getErrors());
        $this->assertSame('Cannot return null for non-nullable field Query.list_of_non_nulls.',
            \array_get($response->getErrors(), '0.message'));
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\Exception
     */
    public function testListOfNonNullsInvalidResponse(): void
    {
        $response = $this->connection(function (FieldResolve $event): void {
            $event->withResult('42');
        })->request(new Request('{ list_of_non_nulls }'));

        $this->assertSame(['list_of_non_nulls' => null], $response->getData());
        $this->assertCount(1, $response->getErrors());
        $this->assertSame('User Error: expected iterable, but did not find one for field Query.list_of_non_nulls.',
            \array_get($response->getErrors(), '0.message'));
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\Exception
     */
    public function testNonNullListDefaultResponse(): void
    {
        $response = $this->connection()->request(new Request('{ non_null_list }'));

        $this->assertNull($response->getData());
        $this->assertCount(1, $response->getErrors());
        $this->assertSame('Cannot return null for non-nullable field Query.non_null_list.',
            \array_get($response->getErrors(), '0.message'));
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function testNonNullListResponse(): void
    {
        $response = $this->connection(function (FieldResolve $event): void {
            $event->withResult(['42']);
        })->request(new Request('{ non_null_list }'));

        $this->assertSame(['non_null_list' => ['42']], $response->getData());
        $this->assertSame([], $response->getErrors());
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function testNonNullListResponseWithNulls(): void
    {
        $response = $this->connection(function (FieldResolve $event): void {
            $event->withResult([null, 42, null]);
        })->request(new Request('{ non_null_list }'));

        $this->assertSame(['non_null_list' => [null, '42', null]], $response->getData());
        $this->assertSame([], $response->getErrors());
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\Exception
     */
    public function testNonNullListInvalidResponse(): void
    {
        $response = $this->connection(function (FieldResolve $event): void {
            $event->withResult('42');
        })->request(new Request('{ non_null_list }'));

        $this->assertNull($response->getData());
        $this->assertCount(1, $response->getErrors());
        $this->assertSame('User Error: expected iterable, but did not find one for field Query.non_null_list.',
            \array_get($response->getErrors(), '0.message'));
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\Exception
     */
    public function testNonNullListOfNonNullsDefaultResponse(): void
    {
        $response = $this->connection()->request(new Request('{ non_null_list_of_non_nulls }'));

        $this->assertNull($response->getData());
        $this->assertCount(1, $response->getErrors());
        $this->assertSame('Cannot return null for non-nullable field Query.non_null_list_of_non_nulls.',
            \array_get($response->getErrors(), '0.message'));
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function testNonNullListOfNonNullsResponse(): void
    {
        $response = $this->connection(function (FieldResolve $event): void {
            $event->withResult(['42']);
        })->request(new Request('{ non_null_list_of_non_nulls }'));

        $this->assertSame(['non_null_list_of_non_nulls' => ['42']], $response->getData());
        $this->assertSame([], $response->getErrors());
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\Exception
     */
    public function testNonNullListOfNonNullsResponseWithNulls(): void
    {
        $response = $this->connection(function (FieldResolve $event): void {
            $event->withResult([null, 42, null]);
        })->request(new Request('{ non_null_list_of_non_nulls }'));

        $this->assertNull($response->getData());
        $this->assertCount(1, $response->getErrors());
        $this->assertSame('Cannot return null for non-nullable field Query.non_null_list_of_non_nulls.',
            \array_get($response->getErrors(), '0.message'));
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\Exception
     */
    public function testNonNullListOfNonNullsInvalidResponse(): void
    {
        $response = $this->connection(function (FieldResolve $event): void {
            $event->withResult('42');
        })->request(new Request('{ non_null_list_of_non_nulls }'));

        $this->assertNull($response->getData());
        $this->assertCount(1, $response->getErrors());
        $this->assertSame('User Error: expected iterable, but did not find one for field Query.non_null_list_of_non_nulls.',
            \array_get($response->getErrors(), '0.message'));
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function testRenderableObject(): void
    {
        $obj = new class() {
            public function __toString(): string
            {
                return '42';
            }
        };

        $response = $this->connection(function (FieldResolve $event) use ($obj): void {
            $event->withResult($obj);
        })->request(new Request('{ value: nullable }'));

        $this->assertSame(['value' => '42'], $response->getData());
        $this->assertSame([], $response->getErrors());
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function testListTraversableObject(): void
    {
        $obj = new class() implements \IteratorAggregate {
            public function getIterator()
            {
                return new \ArrayIterator([1, 2, 3]);
            }
        };

        $response = $this->connection(function (FieldResolve $event) use ($obj): void {
            $event->withResult($obj);
        })->request(new Request('{ value: list }'));

        $this->assertSame(['value' => ['1', '2', '3']], $response->getData());
        $this->assertSame([], $response->getErrors());
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function testListGeneratorObject(): void
    {
        $generator = function () {
            yield 1;
            yield 2;
            yield 3;

            return 42;
        };

        $response = $this->connection(function (FieldResolve $event) use ($generator): void {
            $event->withResult($generator());
        })->request(new Request('{ value: list }'));

        $this->assertSame(['value' => ['1', '2', '3']], $response->getData());
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
                nullable: String
                non_null: String!
                list: [String]
                list_of_non_nulls: [String!]
                non_null_list: [String]!
                non_null_list_of_non_nulls: [String!]!
            }
        ';
    }
}
