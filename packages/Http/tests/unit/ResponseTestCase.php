<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Tests\Unit;

use Railt\Http\Response;
use PHPUnit\Framework\Exception;
use Railt\Http\Extension\Extension;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * Class ResponseTestCase
 */
class ResponseTestCase extends TestCase
{
    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws Exception
     */
    public function testValidResponse(): void
    {
        $response = new Response();

        $this->assertValid($response);
    }

    /**
     * @param Response $response
     * @return void
     * @throws ExpectationFailedException
     * @throws Exception
     */
    private function assertValid(Response $response): void
    {
        $this->assertTrue($response->isValid());
        $this->assertFalse($response->isInvalid());
        $this->assertCount(0, $response->getExceptions());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testResponseData(): void
    {
        $response = new Response([42]);

        $this->assertSame([42], $response->getData());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws Exception
     */
    public function testInvalidResponseUsingConstructor(): void
    {
        $response = new Response(null, [new \Error('error')]);

        $this->assertInvalid($response);
    }

    /**
     * @param Response $response
     * @param int $exceptions
     * @return void
     * @throws ExpectationFailedException
     * @throws Exception
     */
    private function assertInvalid(Response $response, int $exceptions = 1): void
    {
        $this->assertFalse($response->isValid());
        $this->assertTrue($response->isInvalid());
        $this->assertCount($exceptions, $response->getExceptions());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testOriginalExtensionsUsingObjects(): void
    {
        $extensions = [
            new Extension('a', 42),
            new Extension('b', 23),
        ];

        $response = new Response(null, [], $extensions);

        $this->assertSame($extensions, $response->getOriginalExtensions());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testOriginalExtensionsUsingArrays(): void
    {
        $extensions = [
            'a' => 42,
            'b' => 23,
        ];

        $response = new Response(null, [], $extensions);

        [$first, $second] = $response->getOriginalExtensions();

        $this->assertSame('a', $first->getName());
        $this->assertSame(42, $first->getValue());

        $this->assertSame('b', $second->getName());
        $this->assertSame(23, $second->getValue());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws Exception
     */
    public function testExtensionsUniqueKeys(): void
    {
        $response = new Response(null, [], [
            new Extension('a', 23),
            new Extension('a', 42),
        ]);

        $this->assertCount(1, $response->getExtensions());
        $this->assertCount(1, $response->getOriginalExtensions());

        $this->assertArrayHasKey('a', $response->getExtensions());
        $this->assertSame(42, $response->getExtensions()['a']);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testRenderableExtensions(): void
    {
        $response = new Response(null, [], [
            new Extension('a', 23),
            new Extension('b', 42),
        ]);

        $this->assertSame(['a' => 23, 'b' => 42], $response->getExtensions());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testRemovingExtension(): void
    {
        $response = (new Response(null, [], [
            new Extension('a', 23),
            new Extension('b', 42),
        ]))
            ->withoutExtension('a');

        $this->assertSame(['b' => 42], $response->getExtensions());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testRewritingExtension(): void
    {
        $response = (new Response(null, [], [
            new Extension('a', 23),
            new Extension('b', 42),
        ]))
            ->setExtensions([
                new Extension('c', 23),
                new Extension('d', 42),
            ]);

        $this->assertSame(['c' => 23, 'd' => 42], $response->getExtensions());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testRewritingExtensionUsingArrays(): void
    {
        $response = (new Response(null, [], [
            new Extension('a', 23),
            new Extension('b', 42),
        ]))
            ->setExtensions([
                'c' => 23,
                'd' => 42,
            ]);

        $this->assertSame(['c' => 23, 'd' => 42], $response->getExtensions());
    }

    /**
     * @return void
     */
    public function testInvalidExtension(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp(
            '/^First argument should be a name of extension or extension ' .
            'instance, but object\(stdClass\#\d+\) given$/'
        );

        $response = new Response();

        $response->withExtension(new \stdClass());
    }
}
