<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Json;

use PHPUnit\Framework\ExpectationFailedException;
use Railt\Json\Exception\JsonException;
use Railt\Json\Json5;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Class Json5DecoderTestCase
 */
class Json5DecoderTestCase extends AbstractDecoderTestCase
{
    /**
     * @throws ExpectationFailedException
     * @throws JsonException
     * @throws InvalidArgumentException
     */
    public function testVerticalTabChar(): void
    {
        $this->assertSame("\u{000B}", $this->decode('"\v"'));
    }

    /**
     * @param string $value
     * @param int $options
     * @return array|mixed
     * @throws JsonException
     */
    protected function decode(string $value, int $options = 0)
    {
        return Json5::decode($value, $options);
    }

    /**
     * @throws ExpectationFailedException
     * @throws JsonException
     * @throws InvalidArgumentException
     */
    public function testHexValueUpperCase(): void
    {
        $this->assertSame(0xDEADBEEF, $this->decode('0xDEADBEEF'));
    }

    /**
     * @throws ExpectationFailedException
     * @throws JsonException
     * @throws InvalidArgumentException
     */
    public function testHexValueLowerCase(): void
    {
        $this->assertSame(0xDEADBEEF, $this->decode('0xdeadbeef'));
    }

    /**
     * @throws ExpectationFailedException
     * @throws JsonException
     * @throws InvalidArgumentException
     */
    public function testHexNegativeValueUpperCase(): void
    {
        $this->assertSame(-0xDEADBEEF, $this->decode('-0xDEADBEEF'));
    }

    /**
     * @throws ExpectationFailedException
     * @throws JsonException
     * @throws InvalidArgumentException
     */
    public function testHexNegativeValueLowerCase(): void
    {
        $this->assertSame(-0xDEADBEEF, $this->decode('-0xdeadbeef'));
    }

    /**
     * @throws ExpectationFailedException
     * @throws JsonException
     * @throws InvalidArgumentException
     */
    public function testFloatValueWithLeadingFloatingPoint(): void
    {
        $this->assertSame(0.42, $this->decode('.42'));
    }

    /**
     * @throws ExpectationFailedException
     * @throws JsonException
     * @throws InvalidArgumentException
     */
    public function testFloatValueWithTrailingFloatingPoint(): void
    {
        $this->assertSame(42.0, $this->decode('42.'));
    }

    /**
     * @throws ExpectationFailedException
     * @throws JsonException
     * @throws InvalidArgumentException
     */
    public function testFloatNegativeValueWithLeadingFloatingPoint(): void
    {
        $this->assertSame(0.42, $this->decode('.42'));
    }

    /**
     * @throws ExpectationFailedException
     * @throws JsonException
     * @throws InvalidArgumentException
     */
    public function testFloatNegativeValueWithTrailingFloatingPoint(): void
    {
        $this->assertSame(42.0, $this->decode('42.'));
    }

    /**
     * @throws ExpectationFailedException
     * @throws JsonException
     * @throws InvalidArgumentException
     */
    public function testInfValue(): void
    {
        $this->assertSame(\INF, $this->decode('Infinity'));
    }

    /**
     * @throws ExpectationFailedException
     * @throws JsonException
     * @throws InvalidArgumentException
     */
    public function testInfNegativeValue(): void
    {
        $this->assertSame(-\INF, $this->decode('-Infinity'));
    }

    /**
     * @throws ExpectationFailedException
     * @throws JsonException
     * @throws InvalidArgumentException
     */
    public function testNaNValue(): void
    {
        $this->assertNan($this->decode('NaN'));
    }

    /**
     * @throws ExpectationFailedException
     * @throws JsonException
     * @throws InvalidArgumentException
     */
    public function testNaNNegativeValue(): void
    {
        $this->assertNan($this->decode('-NaN'));
    }
}
