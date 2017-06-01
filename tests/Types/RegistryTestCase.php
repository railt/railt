<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Tests\Types;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;
use Serafim\Railgun\Tests\AbstractTestCase;
use Serafim\Railgun\Tests\Mocks\MockCustomType;
use Serafim\Railgun\Types\InternalType;
use Serafim\Railgun\Types\Registry;

/**
 * Class RegistryTestCase
 * @package Serafim\Railgun\Tests\Types
 */
class RegistryTestCase extends AbstractTestCase
{
    /**
     * @var array
     */
    private static $originalTypeNames = [
        'id', // => ID
        'int', // => Int
        'float', // => Float
        'boolean', // => Boolean
        'string' // => String
    ];

    /**
     * @var array
     */
    private static $aliasTypeNames = [
        'integer', 'number', // => Integer
        'real', 'double',  // => Float
        'str', // => String
        'bool' // => Boolean
    ];

    /**
     * @throws AssertionFailedError
     */
    public function testInternalTypes(): void
    {
        $registry = new Registry();

        foreach (self::$originalTypeNames as $original) {
            Assert::assertTrue($registry->isInternal($original));
            Assert::assertFalse($registry->isInternal($original . 's'));
        }

        foreach (self::$aliasTypeNames as $alias) {
            Assert::assertTrue($registry->isInternal($alias));
            Assert::assertFalse($registry->isInternal($alias . 's'));
        }
    }

    /**
     * @throws AssertionFailedError
     */
    public function testInternalTypesAliases(): void
    {
        $registry = new Registry();

        foreach (self::$originalTypeNames as $original) {
            Assert::assertFalse($registry->isAlias($original));
        }

        foreach (self::$aliasTypeNames as $alias) {
            Assert::assertTrue($registry->isAlias($alias));
        }
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     */
    public function testResolveInternalTypes(): void
    {
        $registry = new Registry();

        foreach (self::$originalTypeNames as $original) {
            Assert::assertInstanceOf(InternalType::class, $registry->get($original));
        }

        foreach (self::$aliasTypeNames as $alias) {
            Assert::assertInstanceOf(InternalType::class, $registry->get($alias));
        }
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     */
    public function testBrokenType(): void
    {
        $registry = new Registry();

        $this->expectException(\InvalidArgumentException::class);

        $registry->get('InvalidTypeName');
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     */
    public function testDefinableTypeAlias(): void
    {
        $registry = new Registry();
        $registry->alias(Registry::INTERNAL_TYPE_ID, 'IdAlias');

        Assert::assertInstanceOf(InternalType::class, $registry->get('IdAlias'));
        Assert::assertEquals('id', $registry->get('IdAlias')->getName());
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function testSingleRegistration(): void
    {
        $registry = new Registry();
        $expected = $registry->get('int');

        Assert::assertEquals($expected, $registry->get('integer'));

        // This is super-puper-hack for tests and cant be use in real code. Please +)
        $expected->rename('New Name');

        Assert::assertEquals('NewName', $registry->get('integer')->getName());

        Assert::assertEquals(spl_object_hash($expected), spl_object_hash($registry->get('int')));
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     */
    public function testCustomBadDefinition(): void
    {
        $registry = new Registry();

        $this->expectException(\TypeError::class);

        $registry->add(new \stdClass());
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function testCustomSuccessfulDefinition(): void
    {
        $registry = new Registry();

        $registry->add(new InternalType('test'), 'test');


        // Get by type class
        Assert::assertEquals('test', $registry->get(InternalType::class)->getName());

        // Get by type alias
        Assert::assertEquals('test', $registry->get('test')->getName());
    }

    /**
     * ----------------------
     *  Original <= Aliases:
     * ----------------------
     * 'string' <= 'str' + 'test' + 'test2' + 'test3'
     *
     * @throws AssertionFailedError
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     */
    public function testCreateAliasOfAlias(): void
    {
        $registry = new Registry();

        Assert::assertTrue($registry->isAlias('str'));

        $registry->alias('str', 'test', 'test2');
        $registry->alias('test2', 'test3');

        //
        Assert::assertEquals('string', $registry->get('test3')->getName());
        Assert::assertEquals('string', $registry->get('test2')->getName());
        Assert::assertEquals('string', $registry->get('test')->getName());
        Assert::assertEquals('string', $registry->get('str')->getName());

        // Is aliases?
        Assert::assertTrue($registry->isAlias('test3'));
        Assert::assertTrue($registry->isAlias('test2'));
        Assert::assertTrue($registry->isAlias('test'));
        Assert::assertTrue($registry->isAlias('str'));

        Assert::assertFalse($registry->isAlias('string'));
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function testTypeAutoCreating(): void
    {
        $registry = new Registry();

        Assert::assertEquals('MockCustomType',
            $registry->get(MockCustomType::class)->getName());
    }

    /**
     * @throws AssertionFailedError
     * @throws \InvalidArgumentException
     */
    public function testTypeContains(): void
    {
        $registry = new Registry();

        Assert::assertFalse($registry->has(MockCustomType::class));

        // Check again (returns same?)
        Assert::assertFalse($registry->has(MockCustomType::class));

        // Use automatic type creation
        $registry->get(MockCustomType::class);

        // And again (already registered?)
        Assert::assertTrue($registry->has(MockCustomType::class));
    }

    /**
     * @throws AssertionFailedError
     * @throws \InvalidArgumentException
     */
    public function testTypesCreatingEvents(): void
    {
        $callbackWasCalled = false;

        $registry = new Registry(function(string $type) use (&$callbackWasCalled) {
            $callbackWasCalled = $type;
            return new $type;
        });

        $registry->get(MockCustomType::class);

        Assert::assertNotFalse($callbackWasCalled);
        Assert::assertEquals(MockCustomType::class, $callbackWasCalled);
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     */
    public function testTypesCreatingEventReturnedType(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $registry = new Registry(function(string $name) {
            return null;
        });

        $registry->get(MockCustomType::class);
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     */
    public function testCollectedTypes(): void
    {
        $registry = new Registry();

        Assert::assertCount(0, $registry->all());

        $registry->get(MockCustomType::class);

        Assert::assertCount(1, $registry->all());
    }
}
