<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL\Language\Extend;

use Railt\Reflection\Contracts\Definitions\ObjectDefinition;
use Railt\Reflection\Contracts\Document;
use Railt\SDL\Reflection\Builder\DocumentBuilder;
use Railt\Tests\SDL\Language\AbstractLanguageTestCase;

/**
 * Class ObjectExtensionTestCase
 */
class ObjectExtensionTestCase extends AbstractLanguageTestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    public function provider(): array
    {
        $schema = <<<'GraphQL'
type Test {
    field(id: ID!): String
    id: String
    createdAt: DateTime!
}

extend type Test @deprecated(reason: "Test") {
    field(id: String): ID!
    id(arg: String): ID
    updatedAt: DateTime
}
GraphQL;

        return $this->dataProviderDocuments($schema);
    }

    /**
     * @dataProvider provider
     *
     * @param Document|DocumentBuilder $document
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testType(Document $document): void
    {
        /** @var ObjectDefinition $type */
        $type = $document->getTypeDefinition('Test');

        static::assertNotNull($type);
        static::assertNotNull($type->getField('id'));

        static::assertSame(4, $type->getNumberOfFields());
        static::assertCount(4, $type->getFields());
    }

    /**
     * @dataProvider provider
     *
     * @param Document|DocumentBuilder $document
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testTypeFields(Document $document): void
    {
        /** @var ObjectDefinition $type */
        $type = $document->getTypeDefinition('Test');

        static::assertNotNull($type);

        static::assertTrue($type->hasField('id'));
        static::assertTrue($type->hasField('field'));
        static::assertTrue($type->hasField('createdAt'));
        static::assertTrue($type->hasField('updatedAt'));
        static::assertFalse($type->hasField('not-exists'));
    }

    /**
     * @dataProvider provider
     *
     * @param Document|DocumentBuilder $document
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testTypeArguments(Document $document): void
    {
        /** @var ObjectDefinition $type */
        $type = $document->getTypeDefinition('Test');
        static::assertNotNull($type);

        $field = $type->getField('id');
        static::assertNotNull($field);
        static::assertFalse($field->isNonNull());

        static::assertTrue($field->hasArgument('arg'));
        static::assertNotNull($field->getArgument('arg'));
        static::assertCount(1, $field->getArguments());
    }

    /**
     * @dataProvider provider
     *
     * @param Document|DocumentBuilder $document
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testTypeArgumentsOverriddenLogic(Document $document): void
    {
        /** @var ObjectDefinition $type */
        $type = $document->getTypeDefinition('Test');
        static::assertNotNull($type);

        $field = $type->getField('field');
        static::assertNotNull($field);
        static::assertTrue($field->isNonNull());
        static::assertSame('ID', $field->getTypeDefinition()->getName());

        static::assertTrue($field->hasArgument('id'));
        static::assertFalse($field->getArgument('id')->isNonNull());
        static::assertSame('String', $field->getArgument('id')->getTypeDefinition()->getName());
    }
}
