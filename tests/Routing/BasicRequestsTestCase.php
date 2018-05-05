<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Routing;

use Railt\Tests\TestCase;

/**
 * Class BasicRequestsTestCase
 */
class BasicRequestsTestCase extends TestCase
{
    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testNotNull(): void
    {
        $this->schema('type Query { field: Int! }')
            ->requestRaisesErrors('{ field }')
            ->response('field')
                ->notExists()
            ->errors()
                ->count(1)
            ->error()
                ->hasFields('message', 'in', 'trace')
                ->count(3)
                ->field('message')
                    ->equals('Cannot return null for non-nullable field Query.field.')
        ;

        $this->production->schema('type Query { field: Int! }')
            ->requestRaisesErrors('{ field }')
            ->response('field')
                ->notExists()
            ->errors()
                ->count(1)
            ->error()
                ->hasField('message')
                ->notHasFields('in', 'trace')
                ->count(1)
                ->field('message')
                    ->equals('Internal Server Error')
        ;
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testNotNullList(): void
    {
        $this->schema('type Query { field: [Int]! }')
            ->requestRaisesErrors('{ field }')
            ->response('field')
                ->notExists()
            ->errors()
                ->count(1)
            ->error()
                ->hasFields('message', 'in', 'trace')
                ->count(3)
                ->field('message')
                    ->equals('Cannot return null for non-nullable field Query.field.')
        ;

        $this->production->schema('type Query { field: [Int]! }')
            ->requestRaisesErrors('{ field }')
            ->response('field')
                ->notExists()
            ->errors()
                ->count(1)
            ->error()
                ->hasField('message')
                ->notHasFields('in', 'trace')
                ->count(1)
                ->field('message')
                    ->equals('Internal Server Error')
        ;
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testNotNullListOfNotNulls(): void
    {
        $this->schema('type Query { field: [Int!]! }')
            ->requestRaisesErrors('{ field }')
            ->response('field')
                ->notExists()
            ->errors()
                ->count(1)
            ->error()
                ->hasFields('message', 'in', 'trace')
                ->count(3)
                ->field('message')
                    ->equals('Cannot return null for non-nullable field Query.field.')
        ;

        $this->production->schema('type Query { field: [Int!]! }')
            ->requestRaisesErrors('{ field }')
            ->response('field')
                ->notExists()
            ->errors()
                ->count(1)
            ->error()
                ->hasField('message')
                ->notHasFields('in', 'trace')
                ->count(1)
                ->field('message')
                    ->equals('Internal Server Error')
        ;
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testNull(): void
    {
        $this->schema('type Query { field: Int }')
            ->requestSucceeded('{ field }')
            ->response('field')
                ->exists()
                ->null()
            ->errors()
                ->notExists()
        ;
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testNullableList(): void
    {
        $this->schema('type Query { field: [Int] }')
            ->requestSucceeded('{ field }')
            ->response('field')
                ->exists()
                ->null()
            ->errors()
                ->notExists()
        ;
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testNullableListOfNonNulls(): void
    {
        $this->schema('type Query { field: [Int!] }')
            ->requestSucceeded('{ field }')
            ->response('field')
                ->exists()
                ->null()
            ->errors()
                ->notExists()
        ;
    }
}
