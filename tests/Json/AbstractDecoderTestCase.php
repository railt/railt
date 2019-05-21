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
use PHPUnit\Framework\SkippedTestError;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Class AbstractDecoderTestCase
 */
abstract class AbstractDecoderTestCase extends TestCase
{
    /**
     * @var string
     */
    protected const PHP_INT_MAX_X32 = '2147483647';

    /**
     * @var string
     */
    protected const PHP_INT_MAX_X32_OVERFLOWS = '2147483648';

    /**
     * @var string
     */
    protected const PHP_INT_MAX_X64 = '9223372036854775807';

    /**
     * @var string
     */
    protected const PHP_INT_MAX_X64_OVERFLOWS = '9223372036854775808';

    /**
     * @var string
     */
    protected const PHP_INT_MIN_X32 = '-2147483648';

    /**
     * @var string
     */
    protected const PHP_INT_MIN_X32_OVERFLOWS = '-2147483649';

    /**
     * @var string
     */
    protected const PHP_INT_MIN_X64 = '-9223372036854775808';

    /**
     * @var string
     */
    protected const PHP_INT_MIN_X64_OVERFLOWS = '-9223372036854775809';

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
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
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testUtfString(): void
    {
        $this->assertSame('Привет мир!', $this->decode('"\u041f\u0440\u0438\u0432\u0435\u0442 \u043c\u0438\u0440!"'));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testNewLineChar(): void
    {
        $this->assertSame("\u{000A}", $this->decode('"\n"'));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testBackspaceChar(): void
    {
        $this->assertSame("\u{0008}", $this->decode('"\b"'));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testFormFeedChar(): void
    {
        $this->assertSame("\u{000C}", $this->decode('"\f"'));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testCarriageReturnChar(): void
    {
        $this->assertSame("\u{000D}", $this->decode('"\r"'));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testHorizontalTabChar(): void
    {
        $this->assertSame("\u{0009}", $this->decode('"\t"'));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testIntegerValue(): void
    {
        $this->assertSame(42, $this->decode('42'));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testIntegerExponentValue(): void
    {
        $this->assertSame(4200.0, $this->decode('42e2'));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testNegativeIntegerValue(): void
    {
        $this->assertSame(-42, $this->decode('-42'));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testNegativeIntegerExponentValue(): void
    {
        $this->assertSame(-42000.0, $this->decode('-42e3'));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testIntegerNegativeExponentValue(): void
    {
        $this->assertSame(0.42, $this->decode('42e-2'));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testNegativeIntegerNegativeExponentValue(): void
    {
        $this->assertSame(-0.042, $this->decode('-42e-3'));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testBooleanTrueValue(): void
    {
        $this->assertTrue($this->decode('true'));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testBooleanFalseValue(): void
    {
        $this->assertFalse($this->decode('false'));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testBooleanNullValue(): void
    {
        $this->assertNull($this->decode('null'));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testArrayValue(): void
    {
        $this->assertSame([1, 0.1, true, false, 'string'], $this->decode('[1, 0.1, true, false, "string"]'));
    }

    /**
     * @return void
     */
    public function testObjectValue(): void
    {
        // StyleCI assertEquals -> assertSame replacement fix
        $this->{'assertEquals'}((object)['a' => 1, 'b' => 0.1, 'c' => true, 'd' => false, 'e' => 'string'],
            $this->decode('{"a": 1, "b": 0.1, "c": true, "d": false, "e": "string"}'));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testObjectParsedAsArrayValue(): void
    {
        $this->assertSame(['a' => 1, 'b' => 0.1, 'c' => true, 'd' => false, 'e' => 'string'],
            $this->decode('{"a": 1, "b": 0.1, "c": true, "d": false, "e": "string"}', \JSON_OBJECT_AS_ARRAY));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testBigintValueAsFloat(): void
    {
        $this->assertSame(9.223372036854776e37,
            $this->decode('92233720368547758079223372036854775807'));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testBigintValueAsString(): void
    {
        $this->assertSame('92233720368547758079223372036854775807',
            $this->decode('92233720368547758079223372036854775807', \JSON_BIGINT_AS_STRING));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testBigintExponentValueAsFloat(): void
    {
        $this->assertSame(9.223372036854776e37,
            $this->decode('9.223372036854776e37'));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testBigintExponentValueAsString(): void
    {
        $this->assertSame(9.223372036854776e37,
            $this->decode('9.223372036854776e37', \JSON_BIGINT_AS_STRING));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testIntMaxValue(): void
    {
        $this->assertSame((int)\PHP_INT_MAX,
            $this->decode((string)\PHP_INT_MAX, \JSON_BIGINT_AS_STRING));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws SkippedTestError
     */
    public function testInt32MaxValue(): void
    {
        if (! $this->isX32Platform()) {
            $this->markTestSkipped('This test is not supported on non x86 (32-bits) platforms');
        }

        $this->assertSame((int)self::PHP_INT_MAX_X32,
            $this->decode(self::PHP_INT_MAX_X32, \JSON_BIGINT_AS_STRING));
    }

    /**
     * @return bool
     */
    protected function isX32Platform(): bool
    {
        return \PHP_INT_SIZE === 4;
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws SkippedTestError
     */
    public function testInt64MaxValue(): void
    {
        if (! $this->isX64Platform()) {
            $this->markTestSkipped('This test is not supported on non x64 platforms');
        }

        $this->assertSame((int)self::PHP_INT_MAX_X64,
            $this->decode(self::PHP_INT_MAX_X64, \JSON_BIGINT_AS_STRING));
    }

    /**
     * @return bool
     */
    protected function isX64Platform(): bool
    {
        return \PHP_INT_SIZE === 8;
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws SkippedTestError
     */
    public function testInt32MaxOverflowValue(): void
    {
        if (! $this->isX32Platform()) {
            $this->markTestSkipped('This test is not supported on non x86 (32-bits) platforms');
        }

        $this->assertSame(self::PHP_INT_MAX_X32_OVERFLOWS,
            $this->decode((string)self::PHP_INT_MAX_X32_OVERFLOWS, \JSON_BIGINT_AS_STRING));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws SkippedTestError
     */
    public function testInt32MaxOverflowValueAsFloat(): void
    {
        if (! $this->isX32Platform()) {
            $this->markTestSkipped('This test is not supported on non x86 (32-bits) platforms');
        }

        $this->assertSame((float)self::PHP_INT_MAX_X32_OVERFLOWS,
            $this->decode((string)self::PHP_INT_MAX_X32_OVERFLOWS));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws SkippedTestError
     */
    public function testInt64MaxOverflowValue(): void
    {
        if (! $this->isX64Platform()) {
            $this->markTestSkipped('This test is not supported on non x64 platforms');
        }

        $this->assertSame(self::PHP_INT_MAX_X64_OVERFLOWS,
            $this->decode((string)self::PHP_INT_MAX_X64_OVERFLOWS, \JSON_BIGINT_AS_STRING));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws SkippedTestError
     */
    public function testInt64MaxOverflowValueAsFloat(): void
    {
        if (! $this->isX64Platform()) {
            $this->markTestSkipped('This test is not supported on non x64 platforms');
        }

        $this->assertSame((float)self::PHP_INT_MAX_X64_OVERFLOWS,
            $this->decode((string)self::PHP_INT_MAX_X64_OVERFLOWS));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testIntMinValue(): void
    {
        $this->assertSame((int)\PHP_INT_MIN,
            $this->decode((string)\PHP_INT_MIN, \JSON_BIGINT_AS_STRING));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws SkippedTestError
     */
    public function testInt32MinValue(): void
    {
        if (! $this->isX32Platform()) {
            $this->markTestSkipped('This test is not supported on non x86 (32-bits) platforms');
        }

        $this->assertSame((int)self::PHP_INT_MIN_X32,
            $this->decode(self::PHP_INT_MIN_X32, \JSON_BIGINT_AS_STRING));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws SkippedTestError
     */
    public function testInt64MinValue(): void
    {
        if (! $this->isX64Platform()) {
            $this->markTestSkipped('This test is not supported on non x64 platforms');
        }

        $this->assertSame((int)self::PHP_INT_MIN_X64,
            $this->decode(self::PHP_INT_MIN_X64, \JSON_BIGINT_AS_STRING));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws SkippedTestError
     */
    public function testInt32MinOverflowValue(): void
    {
        if (! $this->isX32Platform()) {
            $this->markTestSkipped('This test is not supported on non x86 (32-bits) platforms');
        }

        $this->assertSame(self::PHP_INT_MIN_X32_OVERFLOWS,
            $this->decode(self::PHP_INT_MIN_X32_OVERFLOWS, \JSON_BIGINT_AS_STRING));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws SkippedTestError
     */
    public function testInt32MinOverflowValueAsFloat(): void
    {
        if (! $this->isX32Platform()) {
            $this->markTestSkipped('This test is not supported on non x86 (32-bits) platforms');
        }

        $this->assertSame((float)self::PHP_INT_MIN_X32_OVERFLOWS,
            $this->decode(self::PHP_INT_MIN_X32_OVERFLOWS));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws SkippedTestError
     */
    public function testInt64MinOverflowValue(): void
    {
        if (! $this->isX64Platform()) {
            $this->markTestSkipped('This test is not supported on non x64 platforms');
        }

        $this->assertSame(self::PHP_INT_MIN_X64_OVERFLOWS,
            $this->decode((string)self::PHP_INT_MIN_X64_OVERFLOWS, \JSON_BIGINT_AS_STRING));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws SkippedTestError
     */
    public function testInt64MinOverflowValueAsFloat(): void
    {
        if (! $this->isX64Platform()) {
            $this->markTestSkipped('This test is not supported on non x64 platforms');
        }

        $this->assertSame((float)self::PHP_INT_MIN_X64_OVERFLOWS,
            $this->decode((string)self::PHP_INT_MIN_X64_OVERFLOWS));
    }
}
