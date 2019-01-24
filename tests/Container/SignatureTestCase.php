<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Container;

use Railt\Container\Container;
use Railt\Container\Exception\ContainerInvocationException;
use Railt\Container\SignatureResolver;

// Preload mock files
// Very-very-very bad practice, but...
require_once __DIR__ . '/mocks.php';

/**
 * Class SignatureTestCase
 */
class SignatureTestCase extends TestCase
{
    /**
     * @return array
     */
    public function positiveProvider(): array
    {
        return [
            'Array Instance'      => [[new MockClass(), 'instanceMethod'], MockClass::class],
            'Array Static'        => [[MockClass::class, 'staticMethod'], MockClass::class],
            'Function'            => ['__test', null],
            'Namespaced Function' => ['Railt\\Tests\\Container\\__test', null],
            'String Static'       => [MockClass::class . '::staticMethod', MockClass::class],
            'String Instance'     => [MockClass::class . '@instanceMethod', MockClass::class],
            'Closure'             => [
                function ($argument) {
                    return $argument;
                },
                null,
            ],
            'Invocable Class'     => [MockClass::class, MockClass::class],
            'Invocable Object'    => [new MockClass(), MockClass::class],
        ];
    }

    /**
     * @return array
     */
    public function negativeProvider(): array
    {
        return [
            'Array Instance Bad Method'  => [[new MockClass(), 'badMethod'], MockClass::class],
            'Array Static Bad Method'    => [[MockClass::class, 'badMethod'], MockClass::class],
            'Array Static Bad Class'     => [['BadMockClass', 'badMethod'], 'BadMockClass'],
            // Undefined function should be parsed as class
            'Bad Function'               => [
                '__bad_function',
                '__bad_function',
            ],
            // Undefined function with namespace should be parsed as class
            'Namespaced Bad Function'    => [
                'Railt\\Tests\\Container\\__bad_function',
                'Railt\\Tests\\Container\\__bad_function',
            ],
            'String Static Bad Method'   => [MockClass::class . '::badMethod', MockClass::class],
            'String Static Bad Class'    => ['BadMockClass::badMethod', 'BadMockClass'],
            'String Instance Bad Method' => [MockClass::class . '@badMethod', MockClass::class],
            'String Instance Bad Class'  => ['BadMockClass@badMethod', 'BadMockClass'],
            'Not Invocable Class'        => [NotCallableClass::class, NotCallableClass::class],
            'Not Invocable Object'       => [new NotCallableClass(), NotCallableClass::class],
            'Bad Class'                  => ['BadMockClass', 'BadMockClass'],
        ];
    }

    /**
     * @dataProvider positiveProvider
     * @param callable|mixed $signature
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testPositiveSignatures($signature): void
    {
        $resolver = new SignatureResolver();

        $this->assertInstanceOf(\Closure::class, $resolver->fetchAction($signature));
    }

    /**
     * @dataProvider negativeProvider
     * @param callable|mixed $signature
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testBadSignaturesParsedAsPositive($signature): void
    {
        $resolver = new SignatureResolver();

        $this->assertNotNull($resolver->match($signature));
    }

    /**
     * @dataProvider positiveProvider
     * @param callable|mixed $signature
     * @param string|null $class
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testPositiveSignatureClasses($signature, ?string $class): void
    {
        $resolver = new SignatureResolver();

        $this->assertSame($class, $resolver->fetchClass($signature));
    }

    /**
     * @dataProvider negativeProvider
     * @param callable|mixed $signature
     * @param string|null $class
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testBadSignaturesClasses($signature, ?string $class): void
    {
        $resolver = new SignatureResolver();

        $this->assertSame($class, $resolver->fetchClass($signature));
    }

    /**
     * @dataProvider negativeProvider
     * @param callable|mixed $signature
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Railt\Container\Exception\ContainerResolutionException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testBadSignaturesIsNotCallable($signature): void
    {
        $this->expectException(ContainerInvocationException::class);

        $container = new Container();

        $expected = \random_int(\PHP_INT_MIN, \PHP_INT_MAX);

        $this->assertSame($expected, $container->call($signature, ['$argument' => $expected]));
    }

    /**
     * @dataProvider positiveProvider
     * @param callable|mixed $signature
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Railt\Container\Exception\ContainerResolutionException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testPositiveSignatureInvocations($signature): void
    {
        $container = new Container();

        $expected = \random_int(\PHP_INT_MIN, \PHP_INT_MAX);

        $this->assertSame($expected, $container->call($signature, ['$argument' => $expected]));
    }
}
