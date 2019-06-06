<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Http;

use PHPUnit\Framework\ExpectationFailedException;
use Railt\Http\Response;

/**
 * Class ResponseTestCase
 */
class ResponseTestCase extends TestCase
{
    /**
     * @param Response $response
     * @return void
     * @throws ExpectationFailedException
     */
    private function assertValid(Response $response): void
    {
        $this->assertTrue($response->isValid());
        $this->assertFalse($response->isInvalid());
        $this->assertCount(0, $response->getExceptions());
    }

    /**
     * @param Response $response
     * @param int $exceptions
     * @return void
     * @throws ExpectationFailedException
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
    public function testValidResponse(): void
    {
        $response = new Response();

        $this->assertValid($response);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testInvalidResponseUsingConstructor(): void
    {
        $response = new Response(null, [new \Error('error')]);

        $this->assertInvalid($response);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testInvalidResponseUsingMutator(): void
    {
        $response = new Response();
        $this->assertValid($response);

        $response->withException(new \InvalidArgumentException('error'));
        $this->assertInvalid($response);

        $response->withException(new \LogicException('error'));
        $this->assertInvalid($response, 2);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testValidResponseUsingMutator(): void
    {
        $response = (new Response())->withException(
            new \LogicException(),
            new \LogicException(),
            new \InvalidArgumentException()
        );

        $this->assertInvalid($response, 3);

        //
        // Remove all \LogicException
        //

        $response->withoutException(static function (\Throwable $e): bool {
            return $e instanceof \InvalidArgumentException;
        });

        $this->assertInvalid($response, 2);

        //
        // Remove all exceptions
        //

        $response->withoutException(static function (\Throwable $e): bool {
            return true;
        });

        $this->assertValid($response);
    }
}
