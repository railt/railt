<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Json;

/**
 * Class AbstractDecoderTestCase
 */
abstract class AbstractDecoderTestCase extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testString(): void
    {
        $this->assertSame('json', $this->decode('"json"'));
    }

    /**
     * @param string $value
     * @param int $options
     * @return mixed
     */
    abstract protected function decode(string $value, int $options = 0);

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testUtfString(): void
    {
        $this->assertSame('Привет мир!', $this->decode('"\u041f\u0440\u0438\u0432\u0435\u0442 \u043c\u0438\u0440!"'));
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testNewLineChar(): void
    {
        $this->assertSame("\u{000A}", $this->decode('"\n"'));
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testBackspaceChar(): void
    {
        $this->assertSame("\u{0008}", $this->decode('"\b"'));
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testFormFeedChar(): void
    {
        $this->assertSame("\u{000C}", $this->decode('"\f"'));
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testCarriageReturnChar(): void
    {
        $this->assertSame("\u{000D}", $this->decode('"\r"'));
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testHorizontalTabChar(): void
    {
        $this->assertSame("\u{0009}", $this->decode('"\t"'));
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testIntegerValue(): void
    {
        $this->assertSame(42, $this->decode('42'));
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testIntegerExponentValue(): void
    {
        $this->assertSame(4200.0, $this->decode('42e2'));
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testNegativeIntegerValue(): void
    {
        $this->assertSame(-42, $this->decode('-42'));
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testNegativeIntegerExponentValue(): void
    {
        $this->assertSame(-42000.0, $this->decode('-42e3'));
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testIntegerNegativeExponentValue(): void
    {
        $this->assertSame(0.42, $this->decode('42e-2'));
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testNegativeIntegerNegativeExponentValue(): void
    {
        $this->assertSame(-0.042, $this->decode('-42e-3'));
    }

    /**
     * @return array|string[]
     */
    public function validCharsDataProvider(): array
    {
        $result = [];
        $string = [];

        for ($i = 0, $len = 0xd800; $i < $len; ++$i) {
            $char = '\u' . \str_pad(\dechex($i), 4, '0', \STR_PAD_LEFT);

            if (\count($string) > 100) {
                $result[] = ['"' . \implode('', $string) . '"'];
                $string = [];
            } else {
                $string[] = $char;
            }
        }

        return $result;
    }

    /**
     * @dataProvider validCharsDataProvider
     * @param string $char
     * @throws \Throwable
     */
    public function testValidUtfCharacters(string $char): void
    {
        $this->assertSame(\json_decode($char), $this->decode($char));
    }
}
