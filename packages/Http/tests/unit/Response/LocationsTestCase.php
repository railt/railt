<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Tests\Unit\Response;

use Railt\Http\GraphQLError;
use PHPUnit\Framework\Exception;
use Railt\Http\Tests\Unit\TestCase;
use Railt\Http\Error\SourceLocation;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * Class LocationsTestCase
 */
class LocationsTestCase extends TestCase
{
    /**
     * @return array
     */
    public function locationProvider(): array
    {
        return [
            'int position' => [[23, 42]],
            'location'     => [[new SourceLocation(23, 42)]],
        ];
    }

    /**
     * @dataProvider locationProvider
     *
     * @param mixed $location
     * @return void
     * @throws ExpectationFailedException
     * @throws Exception
     */
    public function testExceptionLocation($location): void
    {
        $exception = new GraphQLError();

        $exception->withLocation(...$location);

        $this->assertCount(1, $exception->getLocations());

        $location = $exception->getLocations()[0];

        $this->assertSame(23, $location->getLine());
        $this->assertSame(42, $location->getColumn());
    }

    /**
     * @dataProvider locationProvider
     * @param mixed $location
     * @return void
     * @throws ExpectationFailedException
     */
    public function testExceptionLocationSerialization($location): void
    {
        $exception = new GraphQLError();

        $exception->withLocation(...$location);

        $this->assertSame('[{"line":23,"column":42}]', \json_encode($exception->getLocations()));
    }

    /**
     * @dataProvider locationProvider
     * @param mixed $location
     * @return void
     * @throws ExpectationFailedException
     */
    public function testExceptionLocationsExisting($location): void
    {
        $exception = new GraphQLError();

        $exception->withLocation(...$location);

        $this->assertTrue($exception->hasLocations());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws Exception
     */
    public function testInvalidLocation(): void
    {
        $exception = new GraphQLError();

        $exception->withLocation(-1);

        $locations = $exception->getLocations();

        $this->assertCount(1, $locations);
        $this->assertSame(1, $locations[0]->getLine());
        $this->assertSame(1, $locations[0]->getColumn());
    }

    /**
     * @return void
     */
    public function testBadLocationType(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $exception = new GraphQLError();

        $exception->withLocation('some');
    }
}
