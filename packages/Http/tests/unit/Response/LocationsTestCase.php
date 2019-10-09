<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Tests\Unit\Response;

use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use Railt\Http\Exception\GraphQLException;
use Railt\Http\Exception\Location\Location;
use Railt\Http\Tests\Unit\TestCase;

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
            'location'     => [[new Location(23, 42)]],
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
        $exception = new GraphQLException();

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
        $exception = new GraphQLException();

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
        $exception = new GraphQLException();

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
        $exception = new GraphQLException();

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

        $exception = new GraphQLException();

        $exception->withLocation('some');
    }
}
