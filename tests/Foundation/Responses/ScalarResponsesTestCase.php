<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Foundation;

use Illuminate\Support\Arr;
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
     * @throws \PHPUnit\Framework\ExpectationFailedException
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
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testNullableResponse(): void
    {
        $response = $this->request('nullable', '', function () {
            return '42';
        });

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
            Arr::get($response->getErrors(), '0.message'));
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testNonNullResponse(): void
    {
        $response = $this->request('non_null', '', function () {
            return '42';
        });

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
        $response = $this->request('non_null', '', function () {
            return ['42'];
        });

        $this->assertNull($response->getData());
        $this->assertCount(1, $response->getErrors());
        $this->assertSame('Expected a value of type "String" but received: ["42"]',
            Arr::get($response->getErrors(), '0.message'));
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\ExpectationFailedException
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
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testListResponse(): void
    {
        $response = $this->request('list', '', function () {
            return ['42'];
        });

        $this->assertSame(['list' => ['42']], $response->getData());
        $this->assertSame([], $response->getErrors());
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testListResponseWithNulls(): void
    {
        $response = $this->request('list', '', function () {
            return [null, 42, null];
        });

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
        $response = $this->request('list', '', function () {
            return '42';
        });

        $this->assertSame(['list' => null], $response->getData());
        $this->assertCount(1, $response->getErrors());
        $this->assertSame('User Error: expected iterable, but did not find one for field Query.list.',
            Arr::get($response->getErrors(), '0.message'));
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\ExpectationFailedException
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
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testListOfNonNullsResponse(): void
    {
        $response = $this->request('list_of_non_nulls', '', function () {
            return ['42'];
        });

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
        $response = $this->request('list_of_non_nulls', '', function () {
            return [null, 42, null];
        });

        $this->assertSame(['list_of_non_nulls' => null], $response->getData());
        $this->assertCount(1, $response->getErrors());
        $this->assertSame('Cannot return null for non-nullable field Query.list_of_non_nulls.',
            Arr::get($response->getErrors(), '0.message'));
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\Exception
     */
    public function testListOfNonNullsInvalidResponse(): void
    {
        $response = $this->request('list_of_non_nulls', '', function () {
            return '42';
        });

        $this->assertSame(['list_of_non_nulls' => null], $response->getData());
        $this->assertCount(1, $response->getErrors());
        $this->assertSame('User Error: expected iterable, but did not find one for field Query.list_of_non_nulls.',
            Arr::get($response->getErrors(), '0.message'));
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
            Arr::get($response->getErrors(), '0.message'));
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testNonNullListResponse(): void
    {
        $response = $this->request('non_null_list', '', function () {
            return ['42'];
        });

        $this->assertSame(['non_null_list' => ['42']], $response->getData());
        $this->assertSame([], $response->getErrors());
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testNonNullListResponseWithNulls(): void
    {
        $response = $this->request('non_null_list', '', function () {
            return [null, 42, null];
        });

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
        $response = $this->request('non_null_list', '', function () {
            return '42';
        });

        $this->assertNull($response->getData());
        $this->assertCount(1, $response->getErrors());
        $this->assertSame('User Error: expected iterable, but did not find one for field Query.non_null_list.',
            Arr::get($response->getErrors(), '0.message'));
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
            Arr::get($response->getErrors(), '0.message'));
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testNonNullListOfNonNullsResponse(): void
    {
        $response = $this->request('non_null_list_of_non_nulls', '', function () {
            return ['42'];
        });

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
        $response = $this->request('non_null_list_of_non_nulls', '', function () {
            return [null, 42, null];
        });

        $this->assertNull($response->getData());
        $this->assertCount(1, $response->getErrors());
        $this->assertSame('Cannot return null for non-nullable field Query.non_null_list_of_non_nulls.',
            Arr::get($response->getErrors(), '0.message'));
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\Exception
     */
    public function testNonNullListOfNonNullsInvalidResponse(): void
    {
        $response = $this->request('non_null_list_of_non_nulls', '', function () {
            return '42';
        });

        $this->assertNull($response->getData());
        $this->assertCount(1, $response->getErrors());
        $this->assertSame('User Error: expected iterable, but did not find one for field Query.non_null_list_of_non_nulls.',
            Arr::get($response->getErrors(), '0.message'));
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testRenderableObject(): void
    {
        $response = $this->request('value', ':nullable', function () {
            return new class() {
                public function __toString(): string
                {
                    return '42';
                }
            };
        });

        $this->assertSame(['value' => '42'], $response->getData());
        $this->assertSame([], $response->getErrors());
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testListTraversableObject(): void
    {
        $response = $this->request('value', ':list', function () {
            return new class() implements \IteratorAggregate {
                public function getIterator()
                {
                    return new \ArrayIterator([1, 2, 3]);
                }
            };
        });

        $this->assertSame(['value' => ['1', '2', '3']], $response->getData());
        $this->assertSame([], $response->getErrors());
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testListGeneratorObject(): void
    {
        $response = $this->request('value', ':list', function () {
            yield 1;
            yield 2;
            yield 3;

            return 42;
        });

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
