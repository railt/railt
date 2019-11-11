<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Tests\Unit;

use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use Railt\Http\Response;

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
}
