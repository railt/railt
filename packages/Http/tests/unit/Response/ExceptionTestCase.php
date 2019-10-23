<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Tests\Unit\Response;

use PHPUnit\Framework\ExpectationFailedException;
use Railt\Contracts\Http\GraphQLErrorInterface;
use Railt\Http\GraphQLError;
use Railt\Http\Tests\Unit\TestCase;

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
        $exception = new GraphQLError('message');
        $exception->hide();

        $this->assertInternal($exception);
    }

    /**
     * @param GraphQLErrorInterface $exception
     * @return void
     * @throws ExpectationFailedException
     */
    private function assertInternal(GraphQLErrorInterface $exception): void
    {
        $this->assertFalse($exception->isPublic());
        $this->assertSame(GraphQLError::INTERNAL_EXCEPTION_MESSAGE, $exception->getMessage());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testExceptionCode(): void
    {
        $exception = new GraphQLError('', 42);

        $this->assertSame(42, $exception->getCode());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testExceptionPrevious(): void
    {
        $prev = new \InvalidArgumentException('error');

        $exception = new GraphQLError('message', 42, $prev);

        $this->assertSame($prev, $exception->getPrevious());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testPublicExceptionMessage(): void
    {
        $exception = new GraphQLError('message');
        $exception->publish();

        $this->assertPublic($exception, 'message');
    }

    /**
     * @param GraphQLErrorInterface $exception
     * @param string $message
     * @return void
     * @throws ExpectationFailedException
     */
    private function assertPublic(GraphQLErrorInterface $exception, string $message): void
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
        $exception = new GraphQLError('message');

        $this->assertInternal($exception);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testExceptionMultipleStateChanges(): void
    {
        $exception = new GraphQLError('message');

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
        $expected = '{"message":"Internal Server Error","locations":[{"line":1,"column":1}],"path":[]}';

        $this->assertSame($expected, \json_encode(new GraphQLError('message')));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testExceptionWithExtensionJsonSerialization(): void
    {
        $expected = '{"message":"Internal Server Error","locations":[{"line":1,"column":1}],"path":[],"extensions":{"name":"value"}}';

        $this->assertSame($expected, \json_encode(
            (new GraphQLError('message'))
                ->withExtension('name', 'value')
        ));
    }
}
