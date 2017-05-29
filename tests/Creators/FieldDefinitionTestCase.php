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
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Exception;
use Serafim\Railgun\Schema\Creators\ArgumentDefinitionCreator;
use Serafim\Railgun\Schema\Definitions\FieldDefinition;
use Serafim\Railgun\Schema\Definitions\FieldDefinitionInterface;
use Serafim\Railgun\Tests\AbstractTestCase;
use Serafim\Railgun\Schema\Creators\FieldDefinitionCreator;
use Serafim\Railgun\Schema\Definitions\TypeDefinitionInterface;
use Serafim\Railgun\Tests\Concerns\ContainsName;

/**
 * TODO Add then(...) tests
 * TODO Add withArgument(...) tests
 *
 * Class FieldDefinitionTestCase
 * @package Serafim\Railgun\Tests\Creators
 */
class FieldDefinitionTestCase extends AbstractTestCase
{
    use ContainsName;

    /**
     * @return FieldDefinitionInterface
     */
    protected function mock(): FieldDefinitionInterface
    {
        return (new FieldDefinitionCreator('test'))->build();
    }

    /**
     * @throws Exception
     */
    public function testContainsTypeDefinition(): void
    {
        $creator = new FieldDefinitionCreator('test');

        Assert::assertInstanceOf(TypeDefinitionInterface::class, $creator->build()->getTypeDefinition());
    }

    /**
     * @throws AssertionFailedError
     */
    public function testDeprecationDefinition(): void
    {
        $creator = new FieldDefinitionCreator('test');
        Assert::assertFalse($creator->build()->isDeprecated());

        $creator->deprecated('deprecated');
        Assert::assertTrue($creator->build()->isDeprecated());
    }

    /**
     * @throws AssertionFailedError
     */
    public function testDeprecationMessage(): void
    {
        $creator = new FieldDefinitionCreator('test');
        Assert::assertEmpty($creator->build()->getDeprecationReason());

        $creator->deprecated('this field is deprecated');
        Assert::assertEquals('this field is deprecated', $creator->build()->getDeprecationReason());
    }

    /**
     * @throws AssertionFailedError
     */
    public function testDeprecationMessageWithVersion(): void
    {
        $creator = new FieldDefinitionCreator('test');
        Assert::assertEmpty($creator->build()->getDeprecationReason());

        $version = random_int(0, 10) . '.' . random_int(0, 10) . '.' . random_int(0, 10);

        $creator->deprecated('this field is deprecated', $version);
        Assert::assertEquals('this field is deprecated since ' . $version,
            $creator->build()->getDeprecationReason());
    }

    /**
     * @return void
     */
    public function testDefaultName(): void
    {
        $creator = new FieldDefinitionCreator('test');

        Assert::assertEquals('test', $creator->build()->getName());
    }

    /**
     * @return void
     */
    public function testDefaultNameFormatting(): void
    {
        foreach ($this->mockDefaultFormattedName() as $source => $expected) {
            $creator = (new FieldDefinitionCreator('test'))->rename($source);

            Assert::assertEquals($expected, $creator->build()->getName());
        }
    }

    /**
     * @throws AssertionFailedError
     */
    public function testCallbackDefinable(): void
    {
        $creator = (new FieldDefinitionCreator('test'))->then(function() {});

        Assert::assertTrue($creator->build()->isResolvable());
    }

    /**
     * @return void
     */
    public function testCallbackIsResolvable(): void
    {
        [$expected, $actual] = [random_int(PHP_INT_MIN, PHP_INT_MAX), null];

        $creator = (new FieldDefinitionCreator('test'))->then(function() use (&$actual, $expected) {
            $actual = $expected;
        });

        $creator->build()->resolve();

        Assert::assertEquals($expected, $actual);
    }

    /**
     * @throws Exception
     */
    public function testArgumentsDefinable(): void
    {
        $creator = (new FieldDefinitionCreator('test'))
            ->withArgument('name-1', 'type')
            ->withArgument('name-2', 'type');

        Assert::assertCount(2, $creator->build()->getArguments());
    }

    /**
     * @throws Exception
     */
    public function testArgumentsCanBeOverwritten(): void
    {
        $creator = (new FieldDefinitionCreator('test'))
            ->withArgument('name', 'type')
            ->withArgument('name', 'new type');

        Assert::assertCount(1, $creator->build()->getArguments());
    }

    /**
     * @throws AssertionFailedError
     * @throws Exception
     */
    public function testArgumentsCustomizable(): void
    {
        $definition = (new FieldDefinitionCreator('test'))
            ->withArgument('name', 'type', function(ArgumentDefinitionCreator $creator) {
                $creator->required()->many();
            })
            ->build();

        Assert::assertCount(1, $definition->getArguments());

        foreach ($definition->getArguments() as $name => $argument) {
            Assert::assertEquals('name', $name);
            Assert::assertEquals('type', $argument->getTypeDefinition()->getTypeName());
            Assert::assertFalse($argument->getTypeDefinition()->isNullable());
            Assert::assertTrue($argument->getTypeDefinition()->isList());
        }
    }

    public function testNameNotSameArgumentName(): void
    {
        $definition = (new FieldDefinitionCreator('test'))
            ->withArgument('name', 'type', function(ArgumentDefinitionCreator $creator) {
                $creator->rename('argument');
            })
            ->build();

        Assert::assertCount(1, $definition->getArguments());

        foreach ($definition->getArguments() as $name => $argument) {
            Assert::assertEquals('name', $name);
            Assert::assertEquals('argument', $argument->getName());
        }
    }
}
