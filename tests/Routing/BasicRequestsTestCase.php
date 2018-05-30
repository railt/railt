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
        $this->basicQuerySchema('type Query { field: Int! }')
            ->query('{ field }')->send()
                ->status(500)
                ->hasErrors()
            ->where('data.field')
                ->notExists()
            ->where('errors')
                ->count(1)
            ->where('errors.0')
                ->hasFields('message', 'locations', 'path', 'extensions')
                ->count(4)
                ->field('message')
                    ->equals('Cannot return null for non-nullable field Query.field.')
        ;

        $this->production->basicQuerySchema('type Query { field: Int! }')
            ->query('{ field }')->send()
                ->status(500)
                ->hasErrors()
            ->where('data.field')
                ->notExists()
            ->where('errors')
                ->count(1)
            ->where('errors.0')
                ->hasFields('message', 'locations', 'path')
                ->count(3)
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
        $this->basicQuerySchema('type Query { field: [Int]! }')
            ->query('{ field }')->send()
                ->status(500)
                ->hasErrors()
            ->where('data.field')
                ->notExists()
            ->where('errors')
                ->count(1)
            ->where('errors.0')
                ->hasFields('message', 'locations', 'path', 'extensions')
                ->count(4)
                ->field('message')
                    ->equals('Cannot return null for non-nullable field Query.field.')
        ;

        $this->production->basicQuerySchema('type Query { field: [Int]! }')
            ->query('{ field }')->send()
                ->status(500)
                ->hasErrors()
            ->where('data.field')
                ->notExists()
            ->where('errors')
                ->count(1)
            ->where('errors.0')
                ->hasFields('message', 'locations', 'path')
                ->count(3)
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
        $this->basicQuerySchema('type Query { field: [Int!]! }')
            ->query('{ field }')->send()
                ->status(500)
                ->hasErrors()
            ->where('data.field')
                ->notExists()
            ->where('errors')
                ->count(1)
            ->where('errors.0')
                ->hasFields('message', 'locations', 'path', 'extensions')
                ->count(4)
                ->field('message')
                    ->equals('Cannot return null for non-nullable field Query.field.')
        ;

        $this->production->basicQuerySchema('type Query { field: [Int!]! }')
            ->query('{ field }')->send()
                ->status(500)
                ->hasErrors()
            ->where('data.field')
                ->notExists()
            ->where('errors')
                ->count(1)
            ->where('errors.0')
                ->hasFields('message', 'locations', 'path')
                ->count(3)
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
        $this->basicQuerySchema('type Query { field: Int }')
            ->query('{ field }')->send()
                ->status(200)
                ->successful()
            ->where('data.field')
                ->exists()
                ->null()
            ->where('errors')
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
        $this->basicQuerySchema('type Query { field: [Int] }')
            ->query('{ field }')->send()
                ->status(200)
                ->successful()
            ->where('data.field')
                ->exists()
                ->null()
            ->where('errors')
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
        $this->basicQuerySchema('type Query { field: [Int!] }')
            ->query('{ field }')->send()
                ->status(200)
                ->successful()
            ->where('data.field')
                ->exists()
                ->null()
            ->where('errors')
                ->notExists()
        ;
    }
}
