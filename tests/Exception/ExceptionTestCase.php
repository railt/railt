<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Exception;

use Railt\Component\Exception\ExternalException;

/**
 * Class ExceptionTestCase
 */
class ExceptionTestCase extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testMessage(): void
    {
        $exception = new ExternalException('example');
        $this->assertSame('example', $exception->getMessage());

        $exception->withMessage('Example %d', 23);
        $this->assertSame('Example 23', $exception->getMessage());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testCode(): void
    {
        $exception = new ExternalException();
        $this->assertSame(0, $exception->getCode());

        $exception->withCode(42);
        $this->assertSame(42, $exception->getCode());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testPrevious(): void
    {
        $exception = new ExternalException('', 0,
            $prev = new ExternalException()
        );

        $this->assertEquals($prev, $exception->getPrevious());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testFile(): void
    {
        $exception = new ExternalException();
        $this->assertNotSame('example.txt', $exception->getFile());

        $exception->withFile('example.txt');
        $this->assertSame('example.txt', $exception->getFile());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testLine(): void
    {
        $exception = new ExternalException();
        $this->assertNotSame(42, $exception->getLine());

        $exception->withLine(42);
        $this->assertSame(42, $exception->getLine());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testColumn(): void
    {
        $exception = new ExternalException();
        $this->assertNotSame(42, $exception->getColumn());

        $exception->withColumn(42);
        $this->assertSame(42, $exception->getColumn());
    }
}
