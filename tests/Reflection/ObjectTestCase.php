<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Reflection;

use Railt\Reflection\Contracts\Types\ObjectType;

/**
 * Class ObjectTestCase
 */
class ObjectTestCase extends AbstractReflectionTestCase
{
    /**
     * @return array
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \Railt\Parser\Exceptions\CompilerException
     * @throws \Railt\Parser\Exceptions\UnexpectedTokenException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     */
    public function provider(): array
    {
        $schema = 'schema { query: MyQuery, mutation: MyMutation, subscription: MySubscription }' .
            'interface Identifiable { id: ID! }' .
            'type MyQuery implements Identifiable @deprecated(reason: "Because") { id: ID! }' .
            'type MyMutation implements Identifiable @deprecated(reason: "Because") { id: ID! }' .
            'type MySubscription implements Identifiable @deprecated(reason: "Because") { id: ID! }';


        $result = [];
        foreach ($this->getDocuments($schema) as $document) {
            $result[] = [$document->getSchema()->getQuery()];
            $result[] = [$document->getSchema()->getMutation()];
            $result[] = [$document->getSchema()->getSubscription()];
        }

        return $result;
    }

    /**
     * @dataProvider provider
     *
     * @param ObjectType $type
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testObjectName(ObjectType $type): void
    {
        static::assertContains($type->getName(), [
            'MyQuery',
            'MyMutation',
            'MySubscription'
        ]);
    }

    /**
     * @dataProvider provider
     *
     * @param ObjectType $type
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testGetDirectives(ObjectType $type): void
    {
        static::assertArrayHasKey(0, $type->getDirectives());
        static::assertCount(1, $type->getDirectives());

        foreach ($type->getDirectives() as $directive) {
            static::assertEquals('deprecated', $directive->getName());

            static::assertEquals('Because', $directive->getArgument('reason')->getValue());
            static::assertNotNull($directive->getArgument('reason')->getArgument());
            static::assertNull($directive->getArgument('not-exists'));

            static::assertTrue($directive->hasArgument('reason'));
            static::assertFalse($directive->hasArgument('not-exists'));

            static::assertSame(1, $directive->getNumberOfArguments());
        }
    }

    /**
     * @dataProvider provider
     *
     * @param ObjectType $type
     * @return void
     */
    public function testGetDirective(ObjectType $type): void
    {
        static::assertNull($type->getDirective('not-exists'));
        static::assertNotNull($type->getDirective('deprecated'));
    }

    /**
     * @dataProvider provider
     *
     * @param ObjectType $type
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testHasDirective(ObjectType $type): void
    {
        static::assertFalse($type->hasDirective('not-exists'));
        static::assertTrue($type->hasDirective('deprecated'));
    }

    /**
     * @dataProvider provider
     *
     * @param ObjectType $type
     * @return void
     */
    public function testDirectivesCount(ObjectType $type): void
    {
        static::assertSame(1, $type->getNumberOfDirectives());
    }

    /**
     * @dataProvider provider
     *
     * @param ObjectType $type
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testGetFields(ObjectType $type): void
    {
        static::assertArrayHasKey(0, $type->getFields());
        static::assertCount(1, $type->getFields());

        foreach ($type->getFields() as $field) {
            static::assertCount(0, $field->getArguments());
            static::assertSame(0, $field->getNumberOfArguments());
            static::assertSame('Field', $field->getTypeName());
            static::assertCount($field->getNumberOfArguments(), $field->getArguments());
            static::assertSame(0, $field->getNumberOfRequiredArguments());
            static::assertSame(0, $field->getNumberOfOptionalArguments());
            static::assertNull($field->getArgument('some'));
            static::assertNull($field->getArgument('test'));
            static::assertFalse($field->hasArgument('some'));
        }
    }

    /**
     * @dataProvider provider
     *
     * @param ObjectType $type
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testHasField(ObjectType $type): void
    {
        static::assertFalse($type->hasField('not-exist'));
        static::assertTrue($type->hasField('id'));
    }

    /**
     * @dataProvider provider
     *
     * @param ObjectType $type
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testGetField(ObjectType $type): void
    {
        $field = $type->getField('id');

        static::assertNull($type->getField('test'));
        static::assertNotNull($field);

        static::assertEquals('id', $field->getName());
        static::assertCount(0, $field->getArguments());
        static::assertCount(0, $field->getDirectives());
        static::assertEquals(0, $field->getNumberOfArguments());
        static::assertEquals(0, $field->getNumberOfDirectives());
        static::assertEquals(0, $field->getNumberOfOptionalArguments());
        static::assertEquals(0, $field->getNumberOfRequiredArguments());
        static::assertEquals('', $field->getDeprecationReason());
        static::assertFalse($field->isDeprecated());
        static::assertTrue($field->isNonNull());
        static::assertFalse($field->isList());
        static::assertFalse($field->isNonNullList());
        static::assertEquals('ID', $field->getType()->getName());
    }

    /**
     * @dataProvider provider
     *
     * @param ObjectType $type
     * @return void
     */
    public function testFieldsCount(ObjectType $type): void
    {
        static::assertSame(1, $type->getNumberOfFields());
    }

    /**
     * @dataProvider provider
     *
     * @param ObjectType $type
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testInterfaces(ObjectType $type): void
    {
        static::assertCount(1, $type->getInterfaces());
        foreach ($type->getInterfaces() as $interface) {
            static::assertEquals('Identifiable', $interface->getName());
        }
    }

    /**
     * @dataProvider provider
     *
     * @param ObjectType $type
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testHasInterface(ObjectType $type): void
    {
        static::assertFalse($type->hasInterface('test'));
        static::assertTrue($type->hasInterface('Identifiable'));
    }

    /**
     * @dataProvider provider
     *
     * @param ObjectType $type
     * @return void
     */
    public function testGetInterface(ObjectType $type): void
    {
        static::assertNull($type->getInterface('test'));
        static::assertNotNull($type->getInterface('Identifiable'));
    }

    /**
     * @dataProvider provider
     *
     * @param ObjectType $type
     * @return void
     */
    public function testInterfacesCount(ObjectType $type): void
    {
        static::assertSame(1, $type->getNumberOfInterfaces());
    }

    /**
     * @dataProvider provider
     *
     * @param ObjectType $type
     * @return void
     */
    public function testTypeName(ObjectType $type): void
    {
        static::assertEquals('Object', $type->getTypeName());
    }
}
