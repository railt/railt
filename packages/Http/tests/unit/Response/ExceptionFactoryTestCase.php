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
use Railt\Http\Exception\Factory;
use Railt\Http\Tests\Unit\TestCase;
use Railt\Http\Exception\GraphQLException;
use PHPUnit\Framework\ExpectationFailedException;
use Railt\Http\Exception\GraphQLExceptionInterface;
use Railt\Http\Exception\MutableGraphQLExceptionInterface;

/**
 * Class ExceptionFactoryTestCase
 */
class ExceptionFactoryTestCase extends TestCase
{
    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws Exception
     */
    public function testExceptionWrapping(): void
    {
        $exception = new \InvalidArgumentException('message', 42);

        $wrapper = Factory::create($exception);

        // Test exception type
        $this->assertInstanceOf(GraphQLException::class, $wrapper);
        $this->assertInstanceOf(\InvalidArgumentException::class, $wrapper->getPrevious());

        // Test exception mode
        $this->assertInternal($wrapper);

        // Assert other selections
        $this->assertSame($exception->getFile(), $wrapper->getFile());
        $this->assertSame($exception->getLine(), $wrapper->getLine());
    }

    /**
     * @param GraphQLExceptionInterface $exception
     * @return void
     * @throws ExpectationFailedException
     */
    private function assertInternal(GraphQLExceptionInterface $exception): void
    {
        $this->assertFalse($exception->isPublic());
        $this->assertSame(GraphQLException::INTERNAL_EXCEPTION_MESSAGE, $exception->getMessage());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testExceptionWrappingFromSelf(): void
    {
        $exception = new GraphQLException('message', 42);
        $exception->publish();

        $this->assertSame($exception, Factory::create($exception));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testPublicExceptionCreation(): void
    {
        $exception = Factory::public('example');

        $this->assertPublic($exception, 'example');
    }

    /**
     * @param GraphQLExceptionInterface $exception
     * @param string $message
     * @return void
     * @throws ExpectationFailedException
     */
    private function assertPublic(GraphQLExceptionInterface $exception, string $message): void
    {
        $this->assertTrue($exception->isPublic());
        $this->assertSame($message, $exception->getMessage());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testInternalExceptionCreation(): void
    {
        $exception = Factory::internal('a');

        $this->assertInternal($exception);

        if ($exception instanceof MutableGraphQLExceptionInterface) {
            $exception->publish();
            $this->assertPublic($exception, 'a');
        }
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testExceptionsExpand(): void
    {
        $exceptions = [
            $a = new \InvalidArgumentException('a', 1),
            $b = new \InvalidArgumentException('b', 2, $a),
            $c = new \InvalidArgumentException('c', 3, $b),
            $d = new \InvalidArgumentException('d', 4, $c),
        ];

        $this->assertSame(\array_reverse($exceptions), \iterator_to_array(Factory::expand($d)));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testExceptionFirst(): void
    {
        $a = new \InvalidArgumentException('a', 1);
        $b = new \InvalidArgumentException('b', 2, $a);
        $c = new \InvalidArgumentException('c', 3, $b);
        $d = new \InvalidArgumentException('d', 4, $c);

        $this->assertSame($a, Factory::first($d));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testExceptionFirstGraphQLException(): void
    {
        $a = new \InvalidArgumentException('a', 1);
        $b = new GraphQLException('b', 2, $a);
        $c = new GraphQLException('c', 3, $b);
        $d = new \InvalidArgumentException('d', 4, $c);

        $first = Factory::firstGraphQLException($d);

        $this->assertSame($b, $first);
        $this->assertNotSame($c, $first);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testExceptionFirstGraphQLExceptionAuto(): void
    {
        $a = new \InvalidArgumentException('a', 1);
        $b = new \InvalidArgumentException('b', 2, $a);
        $c = new \InvalidArgumentException('c', 3, $b);
        $d = new \InvalidArgumentException('d', 4, $c);

        $first = Factory::firstGraphQLException($d);

        $this->assertInstanceOf(GraphQLException::class, $first);
        $this->assertSame(GraphQLException::INTERNAL_EXCEPTION_MESSAGE, $first->getMessage());

        $this->assertSame(1, $first->getCode());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testExceptionLastGraphQLException(): void
    {
        $a = new \InvalidArgumentException('a', 1);
        $b = new GraphQLException('b', 2, $a);
        $c = new GraphQLException('c', 3, $b);
        $d = new \InvalidArgumentException('d', 4, $c);

        $last = Factory::lastGraphQLException($d);

        $this->assertSame($c, $last);
        $this->assertNotSame($b, $last);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testExceptionLastGraphQLExceptionAuto(): void
    {
        $a = new \InvalidArgumentException('a', 1);
        $b = new \InvalidArgumentException('b', 2, $a);
        $c = new \InvalidArgumentException('c', 3, $b);
        $d = new \InvalidArgumentException('d', 4, $c);

        $last = Factory::lastGraphQLException($d);

        $this->assertInstanceOf(GraphQLException::class, $last);
        $this->assertSame(GraphQLException::INTERNAL_EXCEPTION_MESSAGE, $last->getMessage());
        $this->assertSame(4, $last->getCode());
    }
}
