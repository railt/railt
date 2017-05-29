<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Tests\Definitions;

use PHPUnit\Framework\Assert;
use Serafim\Railgun\Tests\AbstractTestCase;
use Serafim\Railgun\Schema\Definitions\TypeDefinition;

/**
 * Class TypeDefinitionTestCase
 * @package Serafim\Railgun\Tests\Definitions
 */
class TypeDefinitionTestCase extends AbstractTestCase
{
    /**
     * @param string $type
     * @param bool|null $isNullable
     * @param bool|null $isList
     * @return TypeDefinition
     */
    protected function typeDefinition(string $type, ?bool $isNullable = null, ?bool $isList = null)
    {
        if ($isList !== null) {
            return new TypeDefinition($type, (bool)$isNullable, (bool)$isList);
        }

        if ($isNullable !== null) {
            return new TypeDefinition($type, (bool)$isNullable);
        }

        return new TypeDefinition($type);
    }

    /**
     * @return void
     */
    public function testStringType(): void
    {
        $type = $this->typeDefinition('test');

        Assert::assertEquals('test', $type->getTypeName());
    }

    /**
     * @return void
     */
    public function testClassStringType(): void
    {
        $type = $this->typeDefinition(\SomeClass::class);

        Assert::assertEquals(\SomeClass::class, $type->getTypeName());
    }

    /**
     * @return void
     */
    public function testIntType(): void
    {
        $this->expectException(\TypeError::class);

        $this->typeDefinition(random_int(PHP_INT_MIN, PHP_INT_MAX));
    }

    /**
     * @return void
     */
    public function testBoolType(): void
    {
        $this->expectException(\TypeError::class);

        $this->typeDefinition(random_int(0, 1) === 1);
    }

    /**
     * @return void
     */
    public function testFloatType(): void
    {
        $this->expectException(\TypeError::class);

        $this->typeDefinition(random_int(100, 1000) / 100);
    }

    /**
     * @return void
     */
    public function testResourceType(): void
    {
        $this->expectException(\TypeError::class);

        $this->typeDefinition(fopen(__FILE__, 'r+'));
    }

    /**
     * @return void
     */
    public function testArrayType(): void
    {
        $this->expectException(\TypeError::class);

        $this->typeDefinition([random_int(100, 1000), random_int(100, 1000)]);
    }

    /**
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testDefaultListModifier(): void
    {
        $type = $this->typeDefinition('test');
        Assert::assertFalse($type->isList());
    }

    /**
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testPositiveListModifier(): void
    {
        $type = $this->typeDefinition('test', true, true);
        Assert::assertTrue($type->isList());
    }

    /**
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testDefaultNullableModifier(): void
    {
        $type = $this->typeDefinition('test');
        Assert::assertTrue($type->isNullable());
    }

    /**
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testNegativeNullableModifier(): void
    {
        $type = $this->typeDefinition('test', false);
        Assert::assertFalse($type->isNullable());
    }
}
