<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Json;

use Railt\Json\Json5;

/**
 * Class Json5DecoderTestCase
 */
class Json5DecoderTestCase extends AbstractDecoderTestCase
{
    /**
     * @param string $value
     * @param int $options
     * @return array|mixed
     * @throws \Railt\Json\Exception\JsonException
     */
    protected function decode(string $value, int $options = 0)
    {
        return Json5::decoder()
            ->setOptions($options)
            ->withOptions(Json5::FORCE_JSON5_DECODER)
            ->decode($value);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Railt\Json\Exception\JsonException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testVerticalTabChar(): void
    {
        $this->assertSame("\u{000B}", $this->decode('"\v"'));
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Railt\Json\Exception\JsonException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testHexValueUpperCase(): void
    {
        $this->assertSame(0xDEADBEEF, $this->decode('0xDEADBEEF'));
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Railt\Json\Exception\JsonException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testHexValueLowerCase(): void
    {
        $this->assertSame(0xDEADBEEF, $this->decode('0xdeadbeef'));
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Railt\Json\Exception\JsonException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testHexNegativeValueUpperCase(): void
    {
        $this->assertSame(-0xDEADBEEF, $this->decode('-0xDEADBEEF'));
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Railt\Json\Exception\JsonException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testHexNegativeValueLowerCase(): void
    {
        $this->assertSame(-0xDEADBEEF, $this->decode('-0xdeadbeef'));
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Railt\Json\Exception\JsonException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testFloatValueWithLeadingFloatingPoint(): void
    {
        $this->assertSame(0.42, $this->decode('.42'));
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Railt\Json\Exception\JsonException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testFloatValueWithTrailingFloatingPoint(): void
    {
        $this->assertSame(42.0, $this->decode('42.'));
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Railt\Json\Exception\JsonException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testFloatNegativeValueWithLeadingFloatingPoint(): void
    {
        $this->assertSame(0.42, $this->decode('.42'));
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Railt\Json\Exception\JsonException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testFloatNegativeValueWithTrailingFloatingPoint(): void
    {
        $this->assertSame(42.0, $this->decode('42.'));
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Railt\Json\Exception\JsonException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testInfValue(): void
    {
        $this->assertSame(\INF, $this->decode('Infinity'));
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Railt\Json\Exception\JsonException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testInfNegativeValue(): void
    {
        $this->assertSame(-\INF, $this->decode('-Infinity'));
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Railt\Json\Exception\JsonException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testNaNValue(): void
    {
        $this->assertNan($this->decode('NaN'));
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Railt\Json\Exception\JsonException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testNaNNegativeValue(): void
    {
        $this->assertNan($this->decode('-NaN'));
    }
}
