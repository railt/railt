<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Container\Tests\Unit;

use Railt\Container\Container;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use Railt\Container\Exception\ContainerResolutionException;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

/**
 * Class ProxyResolvingTestCase
 */
class ProxyResolvingTestCase extends TestCase
{
    /**
     * @return array
     * @throws InvalidArgumentException
     */
    public function containerDataProvider(): array
    {
        // Prepare Symfony DI Container
        $symfony = new \Symfony\Component\DependencyInjection\Container();
        $symfony->set('locator', new \stdClass());
        $symfony->set(\stdClass::class, new \stdClass());

        // Prepare Laravel DI Container
        $laravel = new \Illuminate\Container\Container();
        $laravel->instance('locator', new \stdClass());
        $laravel->instance(\stdClass::class, new \stdClass());

        // Providers
        return [
            [new Container($symfony)],
            [new Container($laravel)],
        ];
    }

    /**
     * @dataProvider containerDataProvider
     * @param Container $container
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws \ReflectionException
     */
    public function testSelectionByLocatorThroughProxy(Container $container): void
    {
        $this->assertInstanceOf(\stdClass::class, $container->get('locator'));
    }

    /**
     * @dataProvider containerDataProvider
     * @param Container $container
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws \ReflectionException
     */
    public function testSelectionByClassThroughProxy(Container $container): void
    {
        $this->assertInstanceOf(\stdClass::class, $container->get(\stdClass::class));
    }

    /**
     * @dataProvider containerDataProvider
     * @param Container $container
     * @throws Exception
     * @throws \ReflectionException
     */
    public function testServiceNotAllowed(Container $container): void
    {
        $this->expectException(ContainerResolutionException::class);
        $this->expectExceptionMessage('"whoops" entry is not registered');

        $container->get('whoops');
    }

    /**
     * @dataProvider containerDataProvider
     * @param Container $container
     * @throws Exception
     * @throws \ReflectionException
     */
    public function testServiceOverriding(Container $container): void
    {
        try {
            $hasError = false;
            $container->get('test');
        } catch (ContainerResolutionException $e) {
            $hasError = true;
        }

        $this->assertTrue($hasError);

        $container->instance('test', new \stdClass());
        $this->assertInstanceOf(\stdClass::class, $container->get('test'));
    }
}
