<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Tests\Creators;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Exception;
use Serafim\Railgun\Schema\Definitions\ArgumentDefinitionInterface;
use Serafim\Railgun\Tests\AbstractTestCase;
use Serafim\Railgun\Schema\Creators\ArgumentDefinitionCreator;
use Serafim\Railgun\Schema\Definitions\TypeDefinitionInterface;
use Serafim\Railgun\Tests\Concerns\ContainsName;

/**
 * Class ArgumentDefinitionTestCase
 * @package Serafim\Railgun\Tests\Creators
 */
class ArgumentDefinitionTestCase extends AbstractTestCase
{
    use ContainsName;

    /**
     * @return ArgumentDefinitionInterface
     */
    protected function mock(): ArgumentDefinitionInterface
    {
        return (new ArgumentDefinitionCreator('test'))->build();
    }

    /**
     * @throws Exception
     */
    public function testContainsTypeDefinition(): void
    {
        $creator = new ArgumentDefinitionCreator('test');

        Assert::assertInstanceOf(TypeDefinitionInterface::class, $creator->build()->getTypeDefinition());
    }

    /**
     * @return void
     */
    public function testDefaultValue(): void
    {
        $creator = new ArgumentDefinitionCreator('test');

        Assert::assertNull($creator->build()->getDefaultValue());
    }

    /**
     * @return void
     */
    public function testOverwrittenDefaultValue(): void
    {
        $defaults = [
            random_int(PHP_INT_MIN, PHP_INT_MAX), // int
            'test string ' . random_int(PHP_INT_MIN, PHP_INT_MAX), // string
            random_int(PHP_INT_MIN, PHP_INT_MAX) / 1000, // float
            true, // bool
        ];

        foreach ($defaults as $default) {
            $creator = new ArgumentDefinitionCreator('test');

            Assert::assertNull($creator->build()->getDefaultValue());

            $creator->default($default);

            Assert::assertEquals($default, $creator->build()->getDefaultValue());
        }
    }

    /**
     * @return void
     */
    public function testDefaultName(): void
    {
        $creator = new ArgumentDefinitionCreator('test');

        Assert::assertEquals('test', $creator->build()->getName());
    }

    /**
     * @return void
     */
    final public function testDefaultNameFormatting(): void
    {
        foreach ($this->mockDefaultFormattedName() as $source => $expected) {
            $creator = (new ArgumentDefinitionCreator('test'))->rename($source);

            Assert::assertEquals($expected, $creator->build()->getName());
        }
    }
}
