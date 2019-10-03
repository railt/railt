<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Tests\Unit\Response;

use Railt\Http\Tests\Unit\TestCase;
use Railt\Http\Exception\GraphQLException;
use PHPUnit\Framework\ExpectationFailedException;
use Railt\Http\Exception\GraphQLExceptionInterface;

/**
 * Class ExceptionTestCase
 */
class ExceptionTestCase extends TestCase
{
    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testInternalExceptionMessage(): void
    {
        $exception = new GraphQLException('message');
        $exception->hide();

        $this->assertInternal($exception);
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
    public function testExceptionCode(): void
    {
        $exception = new GraphQLException('', 42);

        $this->assertSame(42, $exception->getCode());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testExceptionPrevious(): void
    {
        $prev = new \InvalidArgumentException('error');

        $exception = new GraphQLException('message', 42, $prev);

        $this->assertSame($prev, $exception->getPrevious());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testPublicExceptionMessage(): void
    {
        $exception = new GraphQLException('message');
        $exception->publish();

        $this->assertPublic($exception, 'message');
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
    public function testExceptionMessage(): void
    {
        $exception = new GraphQLException('message');

        $this->assertInternal($exception);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testExceptionMultipleStateChanges(): void
    {
        $exception = new GraphQLException('message');

        $this->assertInternal($exception);

        $exception->publish();

        $this->assertPublic($exception, 'message');

        $exception->hide();

        $this->assertInternal($exception);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testExceptionJsonSerialization(): void
    {
        $expected = '{"message":"Internal Server Error","locations":[],"path":[]}';

        $this->assertSame($expected, \json_encode(new GraphQLException('message')));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testExceptionWithExtensionJsonSerialization(): void
    {
        $expected = '{"message":"Internal Server Error","locations":[],"path":[],"extensions":{"name":"value"}}';

        $this->assertSame($expected, \json_encode(
            (new GraphQLException('message'))
                ->withExtension('name', 'value')
        ));
    }
}
