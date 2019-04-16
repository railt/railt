<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL\Language\Objects;

use Railt\Component\SDL\Contracts\Definitions\ObjectDefinition;
use Railt\Tests\SDL\Language\AbstractLanguageTestCase;

/**
 * Class ObjectTestCase
 */
class ObjectTestCase extends AbstractLanguageTestCase
{
    /**
     * @return array
     * @throws \Exception
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
     * @param ObjectDefinition $type
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testObjectName(ObjectDefinition $type): void
    {
        static::assertContains($type->getName(), [
            'MyQuery',
            'MyMutation',
            'MySubscription',
        ]);
    }

    /**
     * @dataProvider provider
     *
     * @param ObjectDefinition $type
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testGetDirectives(ObjectDefinition $type): void
    {
        static::assertTrue(\is_iterable($type->getDirectives()));
        static::assertCount(1, $type->getDirectives());

        foreach ($type->getDirectives() as $directive) {
            static::assertSame('deprecated', $directive->getName());

            static::assertSame('Because', $directive->getPassedArgument('reason'));
            static::assertNull($directive->getPassedArgument('not-exists'));

            static::assertTrue($directive->hasPassedArgument('reason'));
            static::assertFalse($directive->hasPassedArgument('not-exists'));

            static::assertSame(1, $directive->getNumberOfPassedArguments());
        }
    }

    /**
     * @dataProvider provider
     *
     * @param ObjectDefinition $type
     * @return void
     */
    public function testGetDirective(ObjectDefinition $type): void
    {
        static::assertNull($type->getDirective('not-exists'));
        static::assertNotNull($type->getDirective('deprecated'));
    }

    /**
     * @dataProvider provider
     *
     * @param ObjectDefinition $type
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testHasDirective(ObjectDefinition $type): void
    {
        static::assertFalse($type->hasDirective('not-exists'));
        static::assertTrue($type->hasDirective('deprecated'));
    }

    /**
     * @dataProvider provider
     *
     * @param ObjectDefinition $type
     * @return void
     */
    public function testDirectivesCount(ObjectDefinition $type): void
    {
        static::assertSame(1, $type->getNumberOfDirectives());
    }

    /**
     * @dataProvider provider
     *
     * @param ObjectDefinition $type
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testGetFields(ObjectDefinition $type): void
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
     * @param ObjectDefinition $type
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testHasField(ObjectDefinition $type): void
    {
        static::assertFalse($type->hasField('not-exist'));
        static::assertTrue($type->hasField('id'));
    }

    /**
     * @dataProvider provider
     *
     * @param ObjectDefinition $type
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testGetField(ObjectDefinition $type): void
    {
        $field = $type->getField('id');

        static::assertNull($type->getField('test'));
        static::assertNotNull($field);

        static::assertSame('id', $field->getName());
        static::assertCount(0, $field->getArguments());
        static::assertCount(0, $field->getDirectives());
        static::assertSame(0, $field->getNumberOfArguments());
        static::assertSame(0, $field->getNumberOfDirectives());
        static::assertSame(0, $field->getNumberOfOptionalArguments());
        static::assertSame(0, $field->getNumberOfRequiredArguments());
        static::assertSame('', $field->getDeprecationReason());
        static::assertFalse($field->isDeprecated());
        static::assertTrue($field->isNonNull());
        static::assertFalse($field->isList());
        static::assertFalse($field->isListOfNonNulls());
        static::assertSame('ID', $field->getTypeDefinition()->getName());
    }

    /**
     * @dataProvider provider
     *
     * @param ObjectDefinition $type
     * @return void
     */
    public function testFieldsCount(ObjectDefinition $type): void
    {
        static::assertSame(1, $type->getNumberOfFields());
    }

    /**
     * @dataProvider provider
     *
     * @param ObjectDefinition $type
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testInterfaces(ObjectDefinition $type): void
    {
        static::assertCount(1, $type->getInterfaces());
        foreach ($type->getInterfaces() as $interface) {
            static::assertSame('Identifiable', $interface->getName());
        }
    }

    /**
     * @dataProvider provider
     *
     * @param ObjectDefinition $type
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testHasInterface(ObjectDefinition $type): void
    {
        static::assertFalse($type->hasInterface('test'));
        static::assertTrue($type->hasInterface('Identifiable'));
    }

    /**
     * @dataProvider provider
     *
     * @param ObjectDefinition $type
     * @return void
     */
    public function testGetInterface(ObjectDefinition $type): void
    {
        static::assertNull($type->getInterface('test'));
        static::assertNotNull($type->getInterface('Identifiable'));
    }

    /**
     * @dataProvider provider
     *
     * @param ObjectDefinition $type
     * @return void
     */
    public function testInterfacesCount(ObjectDefinition $type): void
    {
        static::assertSame(1, $type->getNumberOfInterfaces());
    }

    /**
     * @dataProvider provider
     *
     * @param ObjectDefinition $type
     * @return void
     */
    public function testTypeName(ObjectDefinition $type): void
    {
        static::assertSame('Object', $type->getTypeName());
    }
}
