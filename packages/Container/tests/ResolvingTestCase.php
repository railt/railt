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
use Railt\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;
use PHPUnit\Framework\ExpectationFailedException;
use Railt\Container\Exception\ParameterResolutionException;
use Railt\Container\Exception\ContainerInvocationException;

/**
 * Class ResolvingTestCase
 */
class ResolvingTestCase extends TestCase
{
    /**
     * @return array
     */
    public function containerDataProvider(): array
    {
        return [
            [
                new Container(),
            ],
            [
                new Container(
                    new \Symfony\Component\DependencyInjection\Container()
                ),
            ],
            [
                new Container(
                    new \Illuminate\Container\Container()
                ),
            ],
        ];
    }

    /**
     * @dataProvider containerDataProvider
     *
     * @param ContainerInterface $container
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public function testInstanceResolvable(ContainerInterface $container): void
    {
        $container->instance(ContainerInterface::class, $container);

        $this->assertSame($container, $container->get(ContainerInterface::class));
        $this->assertSame($container, $container->make(ContainerInterface::class));
    }

    /**
     * @dataProvider containerDataProvider
     *
     * @param ContainerInterface $container
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public function testSingletonResolvable(ContainerInterface $container): void
    {
        $container->register(\stdClass::class, static function () {
            $class = new \stdClass();
            $class->field = \random_int(\PHP_INT_MIN, \PHP_INT_MAX);

            return $class;
        });

        $this->assertInstanceOf(\stdClass::class, $container->get(\stdClass::class));
        $this->assertInstanceOf(\stdClass::class, $container->make(\stdClass::class));

        $this->assertIsInt($container->get(\stdClass::class)->field);
        $this->assertIsInt($container->make(\stdClass::class)->field);

        $this->assertSame($container->get(\stdClass::class), $container->get(\stdClass::class));
        $this->assertSame($container->make(\stdClass::class), $container->make(\stdClass::class));
    }

    /**
     * @dataProvider containerDataProvider
     *
     * @param ContainerInterface $container
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public function testAutoResolvable(ContainerInterface $container): void
    {
        $this->assertInstanceOf(\stdClass::class, $container->make(\stdClass::class));
    }

    /**
     * @dataProvider containerDataProvider
     *
     * @param ContainerInterface $container
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testNotResolvable(ContainerInterface $container): void
    {
        $this->expectException(NotFoundExceptionInterface::class);

        $container->get(\stdClass::class);
    }

    /**
     * @dataProvider containerDataProvider
     *
     * @param ContainerInterface $container
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public function testAliasResolvable(ContainerInterface $container): void
    {
        $instance = new \stdClass();

        $container->instance(\stdClass::class, $instance);
        $container->alias(\stdClass::class, 'some');

        $this->assertInstanceOf(\stdClass::class, $container->get('some'));
        $this->assertInstanceOf(\stdClass::class, $container->get(\stdClass::class));
        $this->assertSame($container->get('some'), $container->get(\stdClass::class));

        $this->assertInstanceOf(\stdClass::class, $container->make('some'));
        $this->assertInstanceOf(\stdClass::class, $container->make(\stdClass::class));
        $this->assertSame($container->make('some'), $container->make(\stdClass::class));
    }

    /**
     * @dataProvider containerDataProvider
     *
     * @param ContainerInterface $container
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testAliasNotResolvable(ContainerInterface $container): void
    {
        $this->expectException(NotFoundExceptionInterface::class);

        $container->alias(\stdClass::class, 'some');

        $container->get('some');
    }

    /**
     * @dataProvider containerDataProvider
     *
     * @param ContainerInterface $container
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws ContainerInvocationException
     */
    public function testAutowireable(ContainerInterface $container): void
    {
        $container->instance(\stdClass::class, new \stdClass());

        $fn = fn (\stdClass $obj): \stdClass => $obj;

        $this->assertInstanceOf(\stdClass::class, $container->call($fn));
    }

    /**
     * @dataProvider containerDataProvider
     *
     * @param ContainerInterface $container
     * @throws Exception
     * @throws ContainerInvocationException
     */
    public function testUnresolvableAutowiring(ContainerInterface $container): void
    {
        $this->expectException(ParameterResolutionException::class);

        $fn = fn (\stdClass $obj): \stdClass => $obj;

        $this->assertInstanceOf(\stdClass::class, $container->call($fn));
    }

    /**
     * @dataProvider containerDataProvider
     *
     * @param ContainerInterface $container
     * @throws \Exception
     */
    public function testAutowiringWithAdditionalParameters(ContainerInterface $container): void
    {
        [$i, $j] = [\random_int(\PHP_INT_MIN, \PHP_INT_MAX), \random_int(\PHP_INT_MIN, \PHP_INT_MAX)];
        $obj = new \stdClass();
        $obj->value = \random_int(\PHP_INT_MIN, \PHP_INT_MAX);

        $container->instance(\stdClass::class, $obj);


        $result = $container->call(function (\stdClass $c, int $a1, int $a2): string {
            return $c->value . ' ' . $a1 . ' ' . $a2;
        }, ['$a1' => $i, '$a2' => $j]);

        $this->assertSame($obj->value . ' ' . $i . ' ' . $j, $result);
    }
}
